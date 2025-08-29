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
        Schema::create('s_p_p_s', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('siswa_id');
            $table->string('bulan', 100);
            $table->string('tahun', 100);
            $table->string('status', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_p_p_s');
    }
};
