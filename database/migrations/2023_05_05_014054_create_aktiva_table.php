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
        Schema::create('aktiva', function (Blueprint $table) {
            $table->integer('id_aktiva');
            $table->integer('id_kelompok');
            $table->string('nm_aktiva');
            $table->date('tgl');
            $table->double('h_perolehan');
            $table->string('admin');
            $table->double('biaya_depresiasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktiva');
    }
};
