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
        Schema::create('berita_db', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 500);
            $table->string('kategori', 100)->default('Umum')->nullable();
            $table->date('tanggal');
            $table->string('gambar', 500)->nullable();
            $table->text('isi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_db');
    }
};
