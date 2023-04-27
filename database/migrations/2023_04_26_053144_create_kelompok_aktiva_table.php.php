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
        Schema::create('kelompok_aktiva', function (Blueprint $table) {
            $table->integerIncrements('id_kelompok');
            $table->string('nm_kelompok');
            $table->string('umur');
            $table->double('tarif');
            $table->string('barang_kelompok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_aktiva');
    }
};
