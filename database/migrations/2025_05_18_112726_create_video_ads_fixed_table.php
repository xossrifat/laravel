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
        Schema::create('video_ads_fixed', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');  // Can store URL or script code
            $table->string('type')->default('url'); // 'url' or 'script'
            $table->integer('priority')->default(10); // Priority percentage (1-100)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_ads_fixed');
    }
};
