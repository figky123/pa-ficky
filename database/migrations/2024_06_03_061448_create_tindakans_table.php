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
        Schema::create('tindakans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaans');
            $table->date('tgl_tindakan');
            $table->string('ket_tindakan');
            $table->string('bukti_tindakan');
            $table->enum('status_tindakan', ['sudah ditindak', 'belum ditindak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakans');
    }
};
