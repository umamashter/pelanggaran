<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * SecurityAlert — notifikasi keamanan terkirim ke email user (dan in-app via database channel).
 *
 * Tipe yang didukung (TDD §5):
 *  - new_device       : login dari perangkat baru
 *  - new_ip           : login dari IP yang belum pernah dipakai user
 *  - 2fa_enabled      : 2FA berhasil diaktifkan
 *  - 2fa_disabled     : 2FA dinonaktifkan
 *  - recovery_used    : recovery code dipakai saat login
 *  - password_changed : password user diubah
 */
class SecurityAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public string $type;
    public array $data;

    private static array $titles = [
        'new_device'       => 'Login dari Perangkat Baru',
        'new_ip'           => 'Login dari IP Baru',
        '2fa_enabled'      => '2FA Diaktifkan',
        '2fa_disabled'     => '2FA Dinonaktifkan',
        'recovery_used'    => 'Kode Recovery Digunakan',
        'password_changed' => 'Password Diubah',
    ];

    private static array $icons = [
        'new_device'       => 'fa-mobile-screen',
        'new_ip'           => 'fa-location-dot',
        '2fa_enabled'      => 'fa-shield-halved',
        '2fa_disabled'     => 'fa-shield-halved',
        'recovery_used'    => 'fa-key',
        'password_changed' => 'fa-lock',
    ];

    public function __construct(string $type, array $data = [])
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'   => $this->type,
            'title'  => $this->getTitle(),
            'icon'   => $this->getIcon(),
            'data'   => $this->data,
            'time'   => now()->toDateTimeString(),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('[' . config('app.name') . '] ' . $this->getTitle())
            ->greeting('Halo ' . $notifiable->name . ',');

        $this->buildMailBody($mail);

        $mail->line('Jika ini bukan Anda, segera ubah password dan tinjau keamanan akun Anda.')
             ->action('Buka Dashboard', url('/home'))
             ->line('Pesan ini dikirim otomatis oleh sistem keamanan. Jangan balas email ini.');

        return $mail;
    }

    protected function getTitle(): string
    {
        return self::$titles[$this->type] ?? 'Peringatan Keamanan';
    }

    protected function getIcon(): string
    {
        return self::$icons[$this->type] ?? 'fa-bell';
    }

    protected function buildMailBody(MailMessage $mail): void
    {
        $d = $this->data;
        switch ($this->type) {
            case 'new_device':
                $mail->line('Kami mendeteksi login ke akun Anda dari perangkat baru:')
                     ->line('• Browser: ' . ($d['browser'] ?? '—'))
                     ->line('• OS: ' . ($d['os'] ?? '—'))
                     ->line('• Perangkat: ' . ($d['device'] ?? '—'))
                     ->line('• IP Address: ' . ($d['ip'] ?? '—'))
                     ->line('• Waktu: ' . ($d['time'] ?? now()->toDateTimeString()));
                break;
            case 'new_ip':
                $mail->line('Kami mendeteksi login dari alamat IP yang belum pernah Anda gunakan:')
                     ->line('• IP Address: ' . ($d['ip'] ?? '—'))
                     ->line('• Browser: ' . ($d['browser'] ?? '—'))
                     ->line('• Waktu: ' . ($d['time'] ?? now()->toDateTimeString()));
                break;
            case '2fa_enabled':
                $mail->line('Autentikasi Dua Faktor (2FA) telah berhasil diaktifkan pada akun Anda.');
                break;
            case '2fa_disabled':
                $mail->line('Autentikasi Dua Faktor (2FA) telah DINONAKTIFKAN pada akun Anda.')
                     ->line('Jika Anda tidak melakukan ini, segera amankan akun Anda.');
                break;
            case 'recovery_used':
                $mail->line('Salah satu kode recovery Anda telah digunakan untuk login.')
                     ->line('Sisa kode recovery tersisa: ' . ($d['remaining'] ?? '—'))
                     ->line('Pastikan Anda memiliki cadangan kode recovery yang cukup.');
                break;
            case 'password_changed':
                $mail->line('Password akun Anda telah diubah.');
                break;
            default:
                $mail->line('Aktivitas keamanan terdeteksi pada akun Anda.');
        }
    }
}