<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleTwoFaRequirement extends Model
{
    protected $table = 'role_2fa_requirements';

    protected $primaryKey = 'role';

    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'role',
        'require_2fa',
    ];

    protected $casts = [
        'role'         => 'int',
        'require_2fa'  => 'boolean',
    ];

    /**
     * Label peran untuk tampilan.
     */
    public static function roleLabels(): array
    {
        return [
            1 => 'Administrator',
            2 => 'Guru',
            3 => 'Siswa',
            4 => 'BK',
        ];
    }

    public function getRoleLabelAttribute(): string
    {
        return self::roleLabels()[$this->role] ?? ('Role ' . $this->role);
    }

    /**
     * Apakah role tertentu diwajibkan 2FA?
     * Default false bila baris tidak ada (defensif).
     */
    public static function roleRequires(int $role): bool
    {
        return self::where('role', $role)->value('require_2fa') ?? false;
    }
}