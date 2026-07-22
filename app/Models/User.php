<?php

namespace App\Models;

use App\Models\DataSiswa;
use App\Models\AccountActivity;
use App\Models\DeviceFingerprint;
use App\Models\LoginHistory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'nisn',
    //     'name',
    //     'email',
    //     'password',
    //     'info'
    // ];
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
        'recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'info' => 'boolean',
        'birth_date' => 'date',
        'preferences' => 'array',
    ];

    public function siswa()
    {
        return $this->hasOne(Student::class, 'id');
    }

    public function wali()
    {
        return $this->hasOne(WaliKelas::class);
    }
    public function waliKelas()
    {
        return $this->hasOneThrough(WaliKelas::class, Guru::class, 'user_id', 'guru_id');
    }


    public function bk()
    {
        return $this->hasOne(GuruBk::class);
    }

    public function penanganan()
    {
        return $this->hasMany(Penanganan::class);
    }

    public function accountActivities()
    {
        return $this->hasMany(AccountActivity::class);
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function deviceFingerprints()
    {
        return $this->hasMany(DeviceFingerprint::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return asset('storage/' . ltrim($this->avatar_path, '/'));
        }

        $initials = 'U';
        if (!empty($this->name)) {
            $parts = preg_split('/\s+/', trim($this->name)) ?: [];
            $letters = array_map(function ($part) {
                return mb_substr($part, 0, 1);
            }, array_filter($parts));
            $initials = strtoupper(substr(implode('', $letters), 0, 2) ?: 'U');
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="240" height="240" viewBox="0 0 240 240"><rect width="240" height="240" rx="48" fill="#16a34a"/><text x="50%" y="54%" text-anchor="middle" dominant-baseline="middle" font-family="Arial, sans-serif" font-size="92" font-weight="700" fill="#ffffff">' . e($initials) . '</text></svg>';

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    public function preference(string $key, $default = null)
    {
        return data_get($this->preferences ?: [], $key, $default);
    }
    
}
