<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $name = trim((string) ($row['name'] ?? ''));
        $email = trim((string) ($row['email'] ?? ''));
        $role = (int) ($row['role'] ?? 0);
        $nisn = trim((string) ($row['nisn'] ?? ''));

        if ($name === '' || $email === '' || !in_array($role, [1, 2, 3], true)) {
            return null;
        }

        if (User::where('email', $email)->exists()) {
            return null;
        }

        $baseUsername = $nisn !== '' ? $nisn : Str::before($email, '@');
        $baseUsername = preg_replace('/[^A-Za-z0-9_]/', '', (string) $baseUsername) ?: 'user';

        $username = $baseUsername;
        $suffix = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $suffix;
            $suffix++;
        }

        return new User([
            'nisn' => $nisn !== '' ? $nisn : null,
            'username' => $username,
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'info' => 0,
            'password' => Hash::make(
                $nisn !== '' ? $nisn : 'password'
            )
        ]);
    }
}
