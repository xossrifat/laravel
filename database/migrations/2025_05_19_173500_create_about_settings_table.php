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
        Schema::create('about_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('Spin & Earn');
            $table->string('app_version')->default('1.0.0');
            $table->string('app_tagline')->default('The fun way to earn rewards!');
            $table->text('app_description')->nullable();
            $table->json('features_json')->nullable();
            $table->string('support_email')->nullable();
            $table->boolean('live_chat_available')->default(true);
            $table->string('terms_url')->nullable();
            $table->string('privacy_url')->nullable();
            $table->string('cookie_url')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_settings');
    }
};
