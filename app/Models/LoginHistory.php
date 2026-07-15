<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    protected $table = 'login_histories';

    protected $fillable = [
        'user_id',
        'trace_id',
        'session_id',
        'login_at',
        'logout_at',
        'ip_address',
        'user_agent',
        'browser',
        'os',
        'device',
        'device_kind',
        'login_status',
        'otp_status',
        'is_new_device',
        'is_new_ip',
        'metadata',
    ];

    protected $casts = [
        'login_at'      => 'datetime',
        'logout_at'     => 'datetime',
        'is_new_device' => 'boolean',
        'is_new_ip'     => 'boolean',
        'metadata'      => 'array',
    ];

    /**
     * User pemilik riwayat (null bila user tak dikenal saat login gagal).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope: riwayat milik satu user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Label status login (untuk view).
     */
    public function getLoginStatusBadgeAttribute(): string
    {
        switch ($this->login_status) {
            case 'success':   return '<span class="badge bg-success-subtle text-success">Berhasil</span>';
            case 'failed':    return '<span class="badge bg-danger-subtle text-danger">Gagal</span>';
            case 'throttled': return '<span class="badge bg-warning-subtle text-warning">Terblokir</span>';
            default:          return '<span class="badge bg-secondary-subtle text-secondary">' . e($this->login_status) . '</span>';
        }
    }

    /**
     * Label status OTP (untuk view).
     */
    public function getOtpStatusBadgeAttribute(): ?string
    {
        if (!$this->otp_status) {
            return '<span class="text-muted fst-italic">—</span>';
        }
        switch ($this->otp_status) {
            case 'success':       return '<span class="badge bg-success-subtle text-success">OTP Valid</span>';
            case 'failed':        return '<span class="badge bg-danger-subtle text-danger">OTP Gagal</span>';
            case 'not_required':  return '<span class="badge bg-secondary-subtle text-secondary">Tak Perlu</span>';
            case 'recovery':      return '<span class="badge bg-info-subtle text-info">Recovery</span>';
            case 'pending':       return '<span class="badge bg-warning-subtle text-warning">Pending</span>';
            default:              return '<span class="badge bg-secondary-subtle text-secondary">' . e($this->otp_status) . '</span>';
        }
    }
}