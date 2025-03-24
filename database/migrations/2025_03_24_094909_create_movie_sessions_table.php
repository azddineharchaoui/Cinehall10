<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->foreignId('theater_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');
            $table->string('language');
            $table->enum('type', ['Normal', 'VIP']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_sessions');
    }
};