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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('kind')->nullable();
            $table->string('cover_style')->default('sea');
            $table->string('cover_series')->nullable();
            $table->string('cover_author')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('meta')->nullable();
            $table->text('description')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('excerpt_cite')->nullable();
            $table->string('link_label')->nullable();
            $table->string('link_url')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
