<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('google2fa_secret');
            $table->string('phone', 30)->nullable()->after('avatar_path');
            $table->string('gender', 20)->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('gender');
            $table->text('address')->nullable()->after('birth_date');
            $table->json('preferences')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_path', 'phone', 'gender', 'birth_date', 'address', 'preferences']);
        });
    }
};
