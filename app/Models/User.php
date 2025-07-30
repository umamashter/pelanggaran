<?php

namespace App\Models;

use App\Models\DataSiswa;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'info' => 'boolean'
    ];

    public function siswa()
    {
        return $this->hasOne(Student::class, 'id');
    }

    public function wali()
    {
        return $this->hasOne(WaliKelas::class);
    }

    public function bk()
    {
        return $this->hasOne(GuruBk::class);
    }

    public function penanganan()
    {
        return $this->hasMany(Penanganan::class);
    }
    
}