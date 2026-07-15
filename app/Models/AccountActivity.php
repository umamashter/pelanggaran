<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountActivity extends Model
{
    protected $table = 'account_activities';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'metadata',
        'occurred_at',
    ];

    protected $casts = [
        'metadata'    => 'array',
        'occurred_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
