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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('book_id')->nullable();
            $table->string('book_title')->nullable();
            $table->string('book_description')->nullable();
            $table->string('book_creator')->nullable();
            $table->string('book_subject')->nullable();
            $table->string('book_publisher')->nullable();
            $table->string('book_language')->nullable();
            $table->string('book_width')->nullable();
            $table->string('book_height')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
