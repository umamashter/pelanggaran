<?php

namespace App\Models\Traits;

use App\Models\Scopes\HaflahScope;
use App\Models\HaflatulImtihan;

trait BelongsToHaflah
{
    public static function bootBelongsToHaflah()
    {
        static::addGlobalScope(new HaflahScope);

        static::creating(function ($model) {
            if (empty($model->haflah_id) && session('haflah_id')) {
                $model->haflah_id = session('haflah_id');
            }
        });
    }

    public function haflatulImtihan()
    {
        return $this->belongsTo(HaflatulImtihan::class, 'haflah_id');
    }

    public function getIsHaflahSelesaiAttribute(): bool
    {
        if ($this->relationLoaded('haflatulImtihan')) {
            return ($this->haflatulImtihan?->status ?? null) === 'Selesai';
        }

        static $cache = [];
        $id = $this->haflah_id ?? null;
        if ($id === null) {
            return false;
        }
        if (!array_key_exists($id, $cache)) {
            $cache[$id] = HaflatulImtihan::where('id', $id)
                ->where('status', 'Selesai')
                ->exists();
        }
        return $cache[$id];
    }
}
