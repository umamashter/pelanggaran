<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    protected $guarded = ['id'];

    public function mataPelajaran()
    {
        return $this->hasMany(
            MataPelajaran::class,
            'kurikulum_id'
        );
    }
}
