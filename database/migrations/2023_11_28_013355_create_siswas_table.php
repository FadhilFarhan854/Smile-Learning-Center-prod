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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('kelas_id');
            $table->integer('nim');
            $table->string('nama', 255);
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_masuk');
            $table->string('nama_ayah', 255);
            $table->string('nama_ibu', 255);
            $table->string('status', 100);
            $table->date('tanggal_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
