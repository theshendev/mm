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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('kicker')->nullable();
            $table->text('hero_lede')->nullable();
            $table->string('portrait')->nullable();
            $table->text('about_lead')->nullable();
            $table->longText('about_body')->nullable();
            $table->json('facts')->nullable();
            $table->string('writings_intro')->nullable();
            $table->longText('english_body')->nullable();
            $table->string('contact_intro')->nullable();
            $table->string('contact_email')->nullable();
            $table->json('socials')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
