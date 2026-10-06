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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('artist_id')->constrained()->restrictOnDelete();
            $table->foreignId('album_id')->nullable()->constrained()->nullOnDelete(); // NULL = single
            $table->foreignId('genre_id')->constrained()->restrictOnDelete();
            $table->string('title', 150);
            $table->string('file_path', 255);
            $table->string('cover_path', 255)->nullable();
            $table->unsignedInteger('duration')->nullable(); // detik
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
