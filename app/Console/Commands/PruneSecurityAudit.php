<?php

namespace App\Console\Commands;

use App\Models\DeviceFingerprint;
use App\Models\LoginHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * PruneSecurityAudit —Retention policy untuk tabel audit keamanan.
 *
 *  - login_histories: hapus baris berusia > 90 hari (TDD §6 — PII retention).
 *  - device_fingerprints: hapus fingerprint yang last_seen > 1 tahun (idle).
 *
 * Dijadwalkan harian via Console\Kernel::schedule().
 * Dapat juga dipanggil manual: php artisan security:prune
 */
class PruneSecurityAudit extends Command
{
    /** @var string */
    protected $signature = 'security:prune
                            {--history-days=90 : Retensi riwayat login dalam hari}
                            {--fingerprint-days=365 : Retensi fingerprint idle dalam hari}
                            {--dry : Tampilkan jumlah yang akan dihapus tanpa benar-benar menghapus}';

    /** @var string */
    protected $description = 'Hapus riwayat login >90 hari & fingerprint idle >1 tahun (retention policy).';

    public function handle(): int
    {
        $historyDays = (int) $this->option('history-days');
        $fingerprintDays = (int) $this->option('fingerprint-days');
        $dry = $this->option('dry');

        $historyCutoff = now()->subDays($historyDays);
        $fingerprintCutoff = now()->subDays($fingerprintDays);

        $historyQuery = LoginHistory::where('login_at', '<', $historyCutoff);
        $fingerprintQuery = DeviceFingerprint::where('last_seen_at', '<', $fingerprintCutoff);

        $historyCount = $historyQuery->count();
        $fingerprintCount = $fingerprintQuery->count();

        $this->info('Retention policy:');
        $this->line("  login_histories  cutoff: {$historyCutoff}  → {$historyCount} baris akan dihapus");
        $this->line("  device_fingerprints cutoff: {$fingerprintCutoff}  → {$fingerprintCount} baris akan dihapus");

        if ($dry) {
            $this->warn('Mode DRY — tidak ada yang dihapus.');
            return 0;
        }

        if ($historyCount > 0) {
            $historyQuery->delete();
            $this->info("✓ Dihapus {$historyCount} baris login_histories (>{$historyDays} hari).");
        }

        if ($fingerprintCount > 0) {
            $fingerprintQuery->delete();
            $this->info("✓ Dihapus {$fingerprintCount} baris device_fingerprints idle (>{$fingerprintDays} hari).");
        }

        if ($historyCount === 0 && $fingerprintCount === 0) {
            $this->comment('Tidak ada yang perlu dihapus.');
        }

        Log::info('security:prune executed', [
            'history_deleted'   => $historyCount,
            'fingerprint_deleted'=> $fingerprintCount,
            'history_days'      => $historyDays,
            'fingerprint_days'  => $fingerprintDays,
        ]);

        return 0;
    }
}