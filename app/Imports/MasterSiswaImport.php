<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Student;
use App\Models\StudentKelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class MasterSiswaImport implements ToCollection
{
    protected $created = 0;
    protected $skipped = 0;

    public function collection(Collection $rows)
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();
        $semesterAktif = $tahunAktif?->semesterAktif;

        if (!$tahunAktif || !$semesterAktif) {
            return;
        }

        foreach ($rows as $row) {
            $cells = $row instanceof Collection ? $row->toArray() : (array) $row;

            if ($this->isHeaderRow($cells)) {
                continue;
            }

            $data = $this->mapRow($cells);

            if ($data['nama'] === '' || $data['nisn'] === '' || strlen($data['nisn']) !== 10 || !ctype_digit($data['nisn'])) {
                $this->skipped++;
                continue;
            }

            $kelasId = $this->resolveKelasId($data['kelas']);
            if (!$kelasId) {
                $this->skipped++;
                continue;
            }

            if (User::where('username', $data['nisn'])->exists() || Student::where('nisn', $data['nisn'])->exists()) {
                $this->skipped++;
                continue;
            }

            if ($data['email'] !== '' && User::where('email', $data['email'])->exists()) {
                $this->skipped++;
                continue;
            }

            DB::transaction(function () use ($data, $kelasId, $tahunAktif, $semesterAktif) {
                $user = User::create([
                    'name' => $data['nama'],
                    'username' => $data['nisn'],
                    'email' => $data['email'] !== '' ? $data['email'] : null,
                    'role' => 3,
                    'password' => Hash::make($data['nisn']),
                    'info' => 0,
                ]);

                $student = Student::create([
                    'user_id' => $user->id,
                    'nisn' => $data['nisn'],
                    'nama' => $data['nama'],
                    'ttl' => $data['ttl'],
                    'jk' => $data['jk'],
                    'agama' => $data['agama'] ?: 'Islam',
                    'alamat' => $data['alamat'],
                    'no_telp' => $data['no_telp'],
                    'n_ayah' => $data['n_ayah'],
                    'n_ibu' => $data['n_ibu'],
                    'alamat_ortu' => $data['alamat_ortu'],
                    'no_telp_rumah' => $data['no_telp_rumah'],
                    'status' => 'aktif',
                ]);

                StudentKelas::create([
                    'student_id' => $student->id,
                    'kelas_id' => $kelasId,
                    'tahun_ajaran_id' => $tahunAktif->id,
                    'semester_id' => $semesterAktif->id,
                    'aktif' => true,
                ]);
            });

            $this->created++;
        }
    }

    public function createdCount()
    {
        return $this->created;
    }

    public function skippedCount()
    {
        return $this->skipped;
    }

    protected function mapRow(array $cells): array
    {
        return [
            'nama' => trim((string) $this->pick($cells, ['nama', 'name', 'nama_siswa', 'student_name'], 0)),
            'nisn' => trim((string) $this->pick($cells, ['nisn', 'n i s n'], 1)),
            'email' => trim((string) $this->pick($cells, ['email', 'e_mail', 'mail'], 2)),
            'ttl' => trim((string) $this->pick($cells, ['ttl', 'tempat_lahir', 'tempat'], 3)),
            'jk' => trim((string) $this->pick($cells, ['jk', 'jenis_kelamin', 'gender'], 4)),
            'agama' => trim((string) $this->pick($cells, ['agama', 'religion'], 5)),
            'alamat' => trim((string) $this->pick($cells, ['alamat', 'address'], 6)),
            'no_telp' => $this->clean($this->pick($cells, ['no_telp', 'no_hp', 'telepon', 'phone'], 7)),
            'n_ayah' => trim((string) $this->pick($cells, ['n_ayah', 'nama_ayah', 'ayah'], 8)),
            'n_ibu' => trim((string) $this->pick($cells, ['n_ibu', 'nama_ibu', 'ibu'], 9)),
            'alamat_ortu' => trim((string) $this->pick($cells, ['alamat_ortu', 'alamat_orangtua', 'alamat_wali'], 10)),
            'no_telp_rumah' => $this->clean($this->pick($cells, ['no_telp_rumah', 'no_hp_wali', 'telepon_rumah'], 11)),
            'kelas' => trim((string) $this->pick($cells, ['kelas_id', 'kelas', 'nama_kelas'], 12)),
        ];
    }

    protected function pick(array $cells, array $keys, $index)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $cells) && $cells[$key] !== null && $cells[$key] !== '') {
                return $cells[$key];
            }
        }

        return $cells[$index] ?? null;
    }

    protected function resolveKelasId($value)
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (ctype_digit($value)) {
            return Kelas::where('id', $value)->value('id');
        }

        return Kelas::where('nama_kelas', $value)->value('id');
    }

    protected function clean($value)
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? null : $value;
    }

    protected function isHeaderRow(array $cells)
    {
        $values = array_map(function ($value) {
            return strtolower(str_replace(' ', '', trim((string) $value)));
        }, array_values($cells));

        $headers = ['nama', 'name', 'namasiswa', 'nisn', 'email', 'ttl', 'tempatlahir', 'jk', 'jeniskelamin', 'agama', 'alamat', 'notelp', 'kelasid', 'kelas'];

        foreach ($values as $value) {
            if (in_array($value, $headers, true)) {
                return true;
            }
        }

        return false;
    }
}
