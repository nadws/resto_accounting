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
        Schema::create('jurnal', function (Blueprint $table) {
            $table->integerIncrements('id_jurnal');
            $table->date('tgl');
            $table->index('tgl');
            $table->integer('id_akun');
            $table->index('id_akun');
            $table->integer('id_buku');
            $table->index('id_buku');
            $table->string('no_nota');
            $table->string('ket');
            $table->string('no_dokumen');
            $table->date('tgl_dokumen');
            $table->index('tgl_dokumen');
            $table->double('debit');
            $table->double('kredit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal');
    }
};
