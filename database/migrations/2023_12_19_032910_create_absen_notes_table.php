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
        Schema::create('absen_notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('siswa_id');
            $table->string('bulan', 100);
            $table->string('tahun', 100);
            $table->text('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_notes');
    }
};
