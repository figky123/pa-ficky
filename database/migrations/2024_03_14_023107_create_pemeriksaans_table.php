<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_laporan');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_laporan')->references('id')->on('laporans');
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('siklus');
            $table->boolean('kaleng_bekas')->default(0);
            $table->boolean('pecahan_botol')->default(0);
            $table->boolean('ban_bekas')->default(0);
            $table->boolean('tempayan')->default(0);
            $table->boolean('bak_mandi')->default(0);
            $table->boolean('lain_lain')->default(0);
            $table->string('bukti_pemeriksaan');
            $table->string('ket_pemeriksaan');
            $table->enum('tindakan', ['fogging', 'penyuluhan','tidak ada']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
