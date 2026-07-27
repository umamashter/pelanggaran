<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_madrasah', function (Blueprint $table) {
            // Identitas Sekolah
            $table->string('npsn', 20)->nullable()->after('nama_madrasah');
            $table->string('nsm', 20)->nullable()->after('npsn');
            $table->string('nis_nss', 20)->nullable()->after('nsm');
            $table->string('jenjang', 50)->nullable()->after('nis_nss');
            $table->string('status_sekolah', 20)->nullable()->after('jenjang');
            $table->string('status_akreditasi', 10)->nullable()->after('status_sekolah');
            $table->year('tahun_berdiri')->nullable()->after('status_akreditasi');
            $table->string('kurikulum', 50)->nullable()->after('tahun_berdiri');

            // Data Yayasan
            $table->string('nama_yayasan', 150)->nullable()->after('kurikulum');
            $table->string('nomor_akta_yayasan', 50)->nullable()->after('nama_yayasan');
            $table->string('nomor_sk_kemenkumham', 50)->nullable()->after('nomor_akta_yayasan');
            $table->year('tahun_berdiri_yayasan')->nullable()->after('nomor_sk_kemenkumham');
            $table->text('alamat_yayasan')->nullable()->after('tahun_berdiri_yayasan');
            $table->string('nama_ketua_yayasan', 100)->nullable()->after('alamat_yayasan');

            // Alamat & Kontak (komponen alamat, existing alamat/telepon/email tetap dipakai)
            $table->string('desa_kelurahan', 100)->nullable()->after('nama_ketua_yayasan');
            $table->string('kecamatan', 100)->nullable()->after('desa_kelurahan');
            $table->string('kabupaten_kota', 100)->nullable()->after('kecamatan');
            $table->string('provinsi', 100)->nullable()->after('kabupaten_kota');
            $table->string('kode_pos', 10)->nullable()->after('provinsi');
            $table->string('website', 150)->nullable()->after('kode_pos');
            $table->string('whatsapp', 30)->nullable()->after('website');

            // Data Kepala Sekolah
            $table->string('nama_kepala_sekolah', 100)->nullable()->after('whatsapp');
            $table->string('nip_niy', 30)->nullable()->after('nama_kepala_sekolah');
            $table->string('npk', 30)->nullable()->after('nip_niy');
            $table->string('nuptk', 30)->nullable()->after('npk');
            $table->string('nomor_sk_pengangkatan', 50)->nullable()->after('nuptk');
            $table->date('tanggal_sk')->nullable()->after('nomor_sk_pengangkatan');
            $table->string('pendidikan_terakhir', 50)->nullable()->after('tanggal_sk');
        });
    }

    public function down(): void
    {
        Schema::table('profil_madrasah', function (Blueprint $table) {
            $table->dropColumn([
                'npsn', 'nsm', 'nis_nss', 'jenjang', 'status_sekolah', 'status_akreditasi',
                'tahun_berdiri', 'kurikulum',
                'nama_yayasan', 'nomor_akta_yayasan', 'nomor_sk_kemenkumham',
                'tahun_berdiri_yayasan', 'alamat_yayasan', 'nama_ketua_yayasan',
                'desa_kelurahan', 'kecamatan', 'kabupaten_kota', 'provinsi',
                'kode_pos', 'website', 'whatsapp',
                'nama_kepala_sekolah', 'nip_niy', 'npk', 'nuptk',
                'nomor_sk_pengangkatan', 'tanggal_sk', 'pendidikan_terakhir',
            ]);
        });
    }
};
