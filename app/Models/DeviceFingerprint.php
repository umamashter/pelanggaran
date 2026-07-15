<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceFingerprint extends Model
{
    protected $table = 'device_fingerprints';

    protected $fillable = [
        'user_id',
        'fingerprint',
        'browser',
        'os',
        'device',
        'user_agent',
        'is_trusted',
        'first_seen_at',
        'last_seen_at',
    ];

    protected $casts = [
        'is_trusted'   => 'boolean',
        'first_seen_at'=> 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}