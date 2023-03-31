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
        Schema::create('subklasifikasi_akun', function (Blueprint $table) {
            $table->integerIncrements('id_subklasifikasi_akun');
            $table->string('nm_subklasifikasi');
            $table->integer('kode_sub');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subklasifikasi_akun');
    }
};
