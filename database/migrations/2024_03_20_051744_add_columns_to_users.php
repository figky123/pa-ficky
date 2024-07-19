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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();;
            $table->string('password');
            $table->string('no_kk');
            $table->string('no_hp_user');
            $table->string('alamat');
            $table->string('RT');
            $table->string('RW');
            $table->string('foto_kk');
            $table->enum('role', ['Jumantik', 'Puskesmas', 'Lurah', 'Warga', 'Admin']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
