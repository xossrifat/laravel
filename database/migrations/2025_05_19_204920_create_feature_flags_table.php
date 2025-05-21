<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->json('config')->nullable();
            $table->timestamps();
        });
        
        // Add default feature flags
        DB::table('feature_flags')->insert([
            [
                'key' => 'notification_subscription',
                'name' => 'বিজ্ঞপ্তি সাবস্ক্রিপশন',
                'description' => 'আপডেট পেতে চান? - ফিচারটি সক্রিয় করে।',
                'is_enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'nft_marketplace',
                'name' => 'NFT মার্কেটপ্লেস',
                'description' => 'NFT মার্কেটপ্লেস ফিচার - সক্রিয় করলে NFT কেনা-বেচা করা যাবে।',
                'is_enabled' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
    }
};
