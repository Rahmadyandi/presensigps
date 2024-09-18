<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 30)->nullable();
            $table->date('tgl_presensi')->nullable();
            $table->time('jam_in')->nullable();
            $table->time('jam_out')->nullable();
            $table->string('foto_in', 255)->nullable();
            $table->string('foto_out', 255)->nullable();
            $table->text('lokasi_in')->nullable();
            $table->text('lokasi_out')->nullable();
            $table->string('keterangan_in');
            $table->string('keterangan_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
