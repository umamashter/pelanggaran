<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceFingerprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_fingerprints', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Pemilik fingerprint (per-user, bukan global)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            // sha256 hex (browser+os+device+ua_skeleton, TANPA IP)
            $table->string('fingerprint', 64);

            // Metadata perangkat (display)
            $table->string('browser', 50);
            $table->string('os', 50);
            $table->string('device', 50)->nullable();
            $table->text('user_agent')->nullable();

            // User dapat "trust" perangkat → skip notif new_device (Fase 3)
            $table->boolean('is_trusted')->default(false);

            $table->timestamp('first_seen_at')->useCurrent();
            $table->timestamp('last_seen_at')->useCurrent();

            $table->timestamps();

            // Satu fingerprint per user (anti duplikat)
            $table->unique(['user_id', 'fingerprint']);
            $table->index('fingerprint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_fingerprints');
    }
}