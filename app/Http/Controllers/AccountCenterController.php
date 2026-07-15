<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountCenter\UpdateAccountPasswordRequest;
use App\Http\Requests\AccountCenter\UpdateAccountPhotoRequest;
use App\Http\Requests\AccountCenter\UpdateAccountPreferencesRequest;
use App\Http\Requests\AccountCenter\UpdateAccountProfileRequest;
use App\Models\AccountActivity;
use App\Models\DeviceFingerprint;
use App\Models\LoginHistory;
use App\Models\User;
use App\Services\ActiveSessionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AccountCenterController extends Controller
{
    private ActiveSessionService $sessions;

    public function __construct(ActiveSessionService $sessions)
    {
        $this->sessions = $sessions;
    }

    public function index()
    {
        $user = auth()->user();
        $preferences = $this->preferencesFor($user);

        $sessionsData = $this->sessions->listForUser($user->id);
        $devices = DeviceFingerprint::where('user_id', $user->id)
            ->orderByDesc('last_seen_at')
            ->get();

        $activities = Schema::hasTable('account_activities')
            ? AccountActivity::forUser($user->id)
                ->orderByDesc('occurred_at')
                ->limit(8)
                ->get()
            : collect();

        $loginHistories = LoginHistory::forUser($user->id)
            ->orderByDesc('login_at')
            ->limit(8)
            ->get();

        $timeline = $this->buildTimeline($activities, $loginHistories);

        $security = [
            'email' => $user->email ? ($user->email_verified_at ? 'Terverifikasi' : 'Belum terverifikasi') : 'Belum diisi',
            'password' => $this->passwordStatus($user),
            'two_factor' => $user->google2fa_secret ? 'Aktif' : 'Nonaktif',
            'profile' => $user->info ? 'Lengkap' : 'Perlu dilengkapi',
            'sessions' => count($sessionsData['list']),
            'trusted_devices' => $devices->where('is_trusted', true)->count(),
        ];

        return view('account-center.index', [
            'user' => $user,
            'preferences' => $preferences,
            'sessions' => $sessionsData['list'],
            'currentSessionId' => $sessionsData['current_session_id'],
            'devices' => $devices,
            'timeline' => $timeline,
            'security' => $security,
        ]);
    }

    public function updateProfile(UpdateAccountProfileRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        $emailChanged = array_key_exists('email', $validated) && $validated['email'] !== $user->email;

        $user->fill($validated);
        $user->info = true;

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $dirtyFields = array_values(array_diff(array_keys($user->getDirty()), ['info', 'email_verified_at']));

        $user->save();

        if ($dirtyFields && Schema::hasTable('account_activities')) {
            $labels = [
                'name' => 'nama',
                'username' => 'username',
                'email' => 'email',
                'phone' => 'no. hp',
                'gender' => 'jenis kelamin',
                'birth_date' => 'tanggal lahir',
                'address' => 'alamat',
            ];

            AccountActivity::create([
                'user_id' => $user->id,
                'type' => 'profile_updated',
                'title' => 'Profil diperbarui',
                'description' => 'Perubahan tersimpan pada: ' . implode(', ', array_map(function ($field) use ($labels) {
                    return Arr::get($labels, $field, $field);
                }, $dirtyFields)),
                'metadata' => ['fields' => $dirtyFields],
                'occurred_at' => now(),
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePhoto(UpdateAccountPhotoRequest $request)
    {
        $user = $request->user();

        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->avatar_path = $path;
        $user->save();

        if (Schema::hasTable('account_activities')) {
            AccountActivity::create([
                'user_id' => $user->id,
                'type' => 'avatar_updated',
                'title' => 'Foto profil diperbarui',
                'description' => 'Foto profil berhasil diganti.',
                'metadata' => ['avatar_path' => $path],
                'occurred_at' => now(),
            ]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function removePhoto()
    {
        $user = auth()->user();

        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->avatar_path = null;
        $user->save();

        if (Schema::hasTable('account_activities')) {
            AccountActivity::create([
                'user_id' => $user->id,
                'type' => 'avatar_removed',
                'title' => 'Foto profil dihapus',
                'description' => 'Foto profil dikembalikan ke avatar bawaan.',
                'occurred_at' => now(),
            ]);
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    public function updatePassword(UpdateAccountPasswordRequest $request)
    {
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        event(new \App\Events\PasswordChanged($user));

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function updatePreferences(UpdateAccountPreferencesRequest $request)
    {
        $user = $request->user();
        $preferences = array_merge($this->defaultPreferences(), $this->preferencesFor($user), [
            'theme' => $request->input('theme'),
            'language' => $request->input('language'),
            'timezone' => $request->input('timezone'),
            'date_format' => $request->input('date_format'),
            'notify_email' => $request->boolean('notify_email'),
            'notify_whatsapp' => $request->boolean('notify_whatsapp'),
            'notify_browser' => $request->boolean('notify_browser'),
        ]);

        $user->preferences = $preferences;
        $user->save();

        if (Schema::hasTable('account_activities')) {
            AccountActivity::create([
                'user_id' => $user->id,
                'type' => 'preferences_updated',
                'title' => 'Preferensi diperbarui',
                'description' => 'Pengaturan tampilan dan notifikasi disimpan.',
                'metadata' => $preferences,
                'occurred_at' => now(),
            ]);
        }

        return back()->with('success', 'Preferensi berhasil disimpan.');
    }

    private function preferencesFor(User $user): array
    {
        return array_merge($this->defaultPreferences(), (array) ($user->preferences ?? []));
    }

    private function defaultPreferences(): array
    {
        return [
            'theme' => 'system',
            'language' => 'id',
            'timezone' => 'Asia/Jakarta',
            'date_format' => 'd/m/Y',
            'notify_email' => true,
            'notify_whatsapp' => false,
            'notify_browser' => true,
        ];
    }

    private function passwordStatus(User $user): string
    {
        if (!Schema::hasTable('account_activities')) {
            return 'Aktif';
        }

        $changedAt = AccountActivity::forUser($user->id)
            ->where('type', 'password_changed')
            ->latest('occurred_at')
            ->value('occurred_at');

        if ($changedAt) {
            return 'Diubah ' . \Carbon\Carbon::parse($changedAt)->diffForHumans();
        }

        return 'Aktif';
    }

    private function buildTimeline($activities, $loginHistories)
    {
        $items = collect();

        foreach ($activities as $activity) {
            $items->push([
                'type' => 'activity',
                'icon' => $this->iconForActivity($activity->type),
                'title' => $activity->title,
                'description' => $activity->description,
                'meta' => $activity->metadata ?? [],
                'occurred_at' => $activity->occurred_at,
            ]);
        }

        foreach ($loginHistories as $history) {
            $items->push([
                'type' => 'login',
                'icon' => $history->login_status === 'failed' ? 'bi-shield-x' : 'bi-box-arrow-in-right',
                'title' => $history->login_status === 'failed' ? 'Login gagal' : 'Login berhasil',
                'description' => trim(($history->browser ?: 'Unknown') . ' · ' . ($history->os ?: 'Unknown') . ' · ' . ($history->ip_address ?: '-')),
                'meta' => [
                    'status' => $history->login_status,
                    'otp' => $history->otp_status,
                ],
                'occurred_at' => $history->login_at,
            ]);
        }

        return $items->sortByDesc(function ($item) {
            return optional($item['occurred_at'])->timestamp ?? 0;
        })->values();
    }

    private function iconForActivity(string $type): string
    {
        switch ($type) {
            case 'avatar_updated':
            case 'avatar_removed':
                return 'bi-person-badge';
            case 'preferences_updated':
                return 'bi-sliders';
            case 'password_changed':
                return 'bi-shield-lock';
            case '2fa_enabled':
                return 'bi-shield-check';
            case '2fa_disabled':
                return 'bi-shield-exclamation';
            case 'recovery_code_used':
                return 'bi-key';
            default:
                return 'bi-journal-text';
        }
    }
}
