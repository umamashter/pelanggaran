<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppHelper
{
    public static function kirim($nomor, $pesan)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_API_KEY')
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $nomor,
                'message' => $pesan,
            ]);

            Log::info('WA terkirim ke ' . $nomor . ': ' . $response->body());
        } catch (\Exception $e) {
            Log::error("Gagal kirim WA: " . $e->getMessage());
        }
    }
}
