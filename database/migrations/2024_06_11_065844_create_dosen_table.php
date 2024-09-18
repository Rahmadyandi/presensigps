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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('nip');
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('jabatan', 20)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('password', 300)->nullable();
            $table->string('foto', 30)->default('foto.png');
            $table->enum('persetujuan', ['menunggu persetujuan', 'diterima', 'ditolak'])->default('menunggu persetujuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
