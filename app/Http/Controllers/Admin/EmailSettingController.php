<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class EmailSettingController extends Controller
{
    /**
     * Display the email settings form.
     */
    public function index()
    {
        $settings = [
            'mail_mailer' => Setting::get('mail_mailer', 'smtp'),
            'mail_host' => Setting::get('mail_host', 'smtp.mailtrap.io'),
            'mail_port' => Setting::get('mail_port', '2525'),
            'mail_username' => Setting::get('mail_username', ''),
            'mail_password' => Setting::get('mail_password', ''),
            'mail_encryption' => Setting::get('mail_encryption', 'tls'),
            'mail_from_address' => Setting::get('mail_from_address', 'noreply@example.com'),
            'mail_from_name' => Setting::get('mail_from_name', ' RewardBazar'),
            'mail_enabled' => (bool) Setting::get('mail_enabled', false),
        ];

        return view('admin.email_settings.index', compact('settings'));
    }

    /**
     * Update the email settings.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_mailer' => 'required|string|in:smtp,sendmail,mailgun,ses,postmark,log,array',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|numeric',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|in:tls,ssl,null',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.email_settings.index')
                ->withErrors($validator)
                ->withInput();
        }

        $settings = [
            'mail_mailer' => $request->mail_mailer,
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username,
            'mail_password' => $request->filled('mail_password') ? $request->mail_password : Setting::get('mail_password', ''),
            'mail_encryption' => $request->mail_encryption === 'null' ? null : $request->mail_encryption,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
            'mail_enabled' => $request->has('mail_enabled') ? '1' : '0',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.email_settings.index')
            ->with('success', 'Email settings updated successfully.');
    }
    
    /**
     * Send a test email.
     */
    public function testEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.email_settings.index')
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check if mail is enabled
        if (!Setting::get('mail_enabled', false)) {
            return redirect()->route('admin.email_settings.index')
                ->with('error', 'Email sending is disabled. Please enable it first.');
        }

        try {
            // Create a mail configuration from settings
            $config = [
                'driver' => Setting::get('mail_mailer', 'smtp'),
                'host' => Setting::get('mail_host'),
                'port' => Setting::get('mail_port'),
                'from' => [
                    'address' => Setting::get('mail_from_address'),
                    'name' => Setting::get('mail_from_name'),
                ],
                'encryption' => Setting::get('mail_encryption'),
                'username' => Setting::get('mail_username'),
                'password' => Setting::get('mail_password'),
            ];
            
            // Set the configuration
            \Config::set('mail', $config);
            
            // Send test email
            \Mail::raw('This is a test email from  RewardBazar app.', function($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('Test Email from  RewardBazar');
            });
            
            return redirect()->route('admin.email_settings.index')
                ->with('success', 'Test email sent successfully to ' . $request->test_email);
                
        } catch (\Exception $e) {
            return redirect()->route('admin.email_settings.index')
                ->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
