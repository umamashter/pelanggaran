<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relasi: null untuk user tak dikenal (login gagal username inexistent)
            // ON DELETE SET NULL agar audit tetap ada walau user dihapus
            $table->unsignedBigInteger('user_id')->nullable()->index();

            // Korelasi 1 baris per login attempt (anti double-record, TDD §10)
            $table->string('trace_id', 40)->nullable()->index();

            // Soft-ref ke sessions.id (fana, bukan hard FK)
            $table->string('session_id', 255)->nullable()->index();

            $table->timestamp('login_at')->useCurrent();
            $table->timestamp('logout_at')->nullable();

            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();

            // Metadata perangkat (diisi oleh UA parser)
            $table->string('browser', 50)->nullable();
            $table->string('os', 50)->nullable();
            $table->string('device', 50)->nullable();
            $table->string('device_kind', 20)->nullable();

            // Status (Fase 1: success/failed; throttled di fase lanjut)
            $table->string('login_status', 20)->index();
            $table->string('otp_status', 20)->nullable();
            //   null         = password gagal (OTP tidak tercapai)
            //   'success'    = OTP valid (user 2FA final login)
            //   'not_required' = user non-2FA
            //   'failed'     = OTP salah (akan diisi Fase 6 via event custom)
            //   'recovery'   = pakai recovery code (Fase 6)

            // Flag deteksi perangkat/IP baru (diisi Fase 2 & 3, kolom siap)
            $table->boolean('is_new_device')->default(false);
            $table->boolean('is_new_ip')->default(false);

            // Payload tambahan (mis. username percobaan saat gagal)
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Index utama: query history per user kronologis
            $table->index(['user_id', 'login_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_histories');
    }
}