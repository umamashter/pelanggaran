<?php

namespace App\Services;

use App\Models\DeviceFingerprint;
use Illuminate\Http\Request;

/**
 * DeviceFingerprintService — fingerprint perangkat stabil TANPA IP.
 *
 * Tujuan (TDD §9):
 *  - Fingerprint tidak berubah saat IP berganti (ISP/mobile/roaming).
 *  - Versi browser/OS dilucuti agar update versi tidak dianggap perangkat baru.
 *  - Dipakai listener DetectNewDevice untuk tandai login_histories.is_new_device.
 *
 * Tidak menyentuh controller apa pun — listener-driven.
 */
class DeviceFingerprintService
{
    /**
     * Hitung fingerprint dari request.
     *
     * Komposisi (semua dilucuti versinya):
     *   browser_major + os_major + device_family + ua_skeleton
     * IP Address SENGAJA TIDAK ikut — IP dinamis → spam notifikasi.
     *
     * @return string sha256 hex 64 char
     */
    public function compute(Request $request = null): string
    {
        $request = $request ?? request();
        $ua = $request ? $request->userAgent() ?? '' : '';

        $parsed = LoginHistoryService::parseUserAgent($ua);

        // Skeleton UA: ambil token non-versi saja
        // Mis. "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)"
        // Skeleton normalisasi: lowercase + hapus semua digit + collapse whitespace
        $skeleton = $this->skeletonizeUa($ua);

        // Komponen sudah major-family (jenssegers/agent tidak kembalikan versi)
        $payload = implode('|', [
            $parsed['browser'],
            $parsed['os'],
            $parsed['device'],
            $skeleton,
        ]);

        return hash('sha256', $payload);
    }

    /**
     * Lucuti UA menjadi skeleton stabil: hapus nomor versi, lowercase, trim.
     * Hanya menyisakan family-name token utama.
     */
    protected function skeletonizeUa(string $ua): string
    {
        if ($ua === '') return '';
        $s = strtolower($ua);
        // Hapus semua pola "X.Y.Z" dan "X.Y" (versi)
        $s = preg_replace('#\b\d+(\.\d+)+\b#', '', $s);
        // Hapus angka yang berdiri sendiri di utsur token (mis. "NT 10" → "NT")
        $s = preg_replace('#\b\d+\b#', '', $s);
        // Collapse whitespace
        $s = preg_replace('#\s+#', ' ', $s);
        return trim($s);
    }

    /**
     * Resolve atau buat fingerprint user.
     *
     * Idempotent: kalau sudah ada → update last_seen_at, return is_new=false.
     * Kalau baru → insert, return is_new=true.
     *
     * @return array{fingerprint_id:int, is_new:bool, record:DeviceFingerprint}
     */
    public function resolveOrCreate(int $userId, Request $request = null): array
    {
        $request = $request ?? request();
        $ua = $request ? $request->userAgent() : null;
        $parsed = LoginHistoryService::parseUserAgent($ua);
        $fp = $this->compute($request);

        $existing = DeviceFingerprint::where('user_id', $userId)
            ->where('fingerprint', $fp)
            ->first();

        if ($existing) {
            $existing->update(['last_seen_at' => now()]);
            return ['fingerprint_id' => $existing->id, 'is_new' => false, 'record' => $existing];
        }

        $record = DeviceFingerprint::create([
            'user_id'       => $userId,
            'fingerprint'   => $fp,
            'browser'       => $parsed['browser'],
            'os'            => $parsed['os'],
            'device'        => $parsed['device'],
            'user_agent'    => $ua,
            'is_trusted'    => false,
            'first_seen_at' => now(),
            'last_seen_at'  => now(),
        ]);

        return ['fingerprint_id' => $record->id, 'is_new' => true, 'record' => $record];
    }

    /**
     * Tandai perangkat sebagai trusted (skip notif new_device, Fase 3).
     */
    public function markTrusted(int $fingerprintId, int $ownerId): bool
    {
        return DeviceFingerprint::where('id', $fingerprintId)
            ->where('user_id', $ownerId)
            ->update(['is_trusted' => true]) > 0;
    }

    /**
     * Apakah fingerprint ini sudah dikenal user? (tanpa insert)
     */
    public function isKnown(int $userId, string $fingerprint): bool
    {
        return DeviceFingerprint::where('user_id', $userId)
            ->where('fingerprint', $fingerprint)
            ->exists();
    }
}