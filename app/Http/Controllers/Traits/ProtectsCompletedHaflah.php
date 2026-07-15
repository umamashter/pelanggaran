<?php

namespace App\Http\Controllers\Traits;

use App\Models\HaflatulImtihan;
use Illuminate\Http\RedirectResponse;

trait ProtectsCompletedHaflah
{
    protected function haflahSelesai($haflahId): bool
    {
        if (!$haflahId) {
            return false;
        }

        return HaflatulImtihan::where('id', $haflahId)
            ->where('status', 'Selesai')
            ->exists();
    }

    protected function blockIfHaflahSelesai($haflahId): ?RedirectResponse
    {
        if ($this->haflahSelesai($haflahId)) {
            return redirect()
                ->back()
                ->with('error', 'Haflah sudah selesai. Data tidak dapat diubah atau dihapus.');
        }

        return null;
    }

    protected function blockStoreIfHaflahSelesai($haflahId = null): ?RedirectResponse
    {
        $haflahId = $haflahId ?: session('haflah_id');

        if ($this->haflahSelesai($haflahId)) {
            return redirect()
                ->back()
                ->with('error', 'Haflah sudah selesai. Tidak dapat menambah data baru.');
        }

        return null;
    }
}
