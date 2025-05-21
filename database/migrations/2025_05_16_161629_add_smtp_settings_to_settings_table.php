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
        // Insert default SMTP settings into the settings table
        $settings = [
            ['key' => 'mail_mailer', 'value' => 'smtp'],
            ['key' => 'mail_host', 'value' => 'smtp.mailtrap.io'],
            ['key' => 'mail_port', 'value' => '2525'],
            ['key' => 'mail_username', 'value' => ''],
            ['key' => 'mail_password', 'value' => ''],
            ['key' => 'mail_encryption', 'value' => 'tls'],
            ['key' => 'mail_from_address', 'value' => 'noreply@example.com'],
            ['key' => 'mail_from_name', 'value' => ' RewardBazar'],
            ['key' => 'mail_enabled', 'value' => '0'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['key' => $setting['key'], 'value' => $setting['value']]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove SMTP settings from the settings table
        DB::table('settings')->whereIn('key', [
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
            'mail_enabled',
        ])->delete();
    }
};
