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
        // Check if the telegram_bot_username setting exists
        $exists = DB::table('settings')->where('key', 'telegram_bot_username')->exists();
        
        // If it doesn't exist, add it
        if (!$exists) {
            DB::table('settings')->insert([
                'key' => 'telegram_bot_username',
                'value' => 'YourBotUsername',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not remove the setting when rolling back
    }
}; 