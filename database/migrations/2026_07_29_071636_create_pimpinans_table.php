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
        Schema::create('pimpinan_db', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 300);
            $table->string('masa_jabatan', 200);
            $table->string('gambar', 500)->nullable();
            $table->boolean('is_current')->default(0);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pimpinan_db');
    }
};
