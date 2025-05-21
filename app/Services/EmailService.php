<?php

namespace App\Services;

use App\Models\User;
use App\Models\Setting;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\RewardMail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send a reward notification email to a user
     *
     * @param User $user The user to send the email to
     * @param int|float $amount The reward amount
     * @param string $type The reward type (coins, points, etc.)
     * @param array $additionalData Additional data to include in the email
     * @return bool Whether the email was sent successfully
     */
    public function sendRewardEmail(User $user, $amount, $type = 'coins', array $additionalData = []): bool
    {
        // Check if mail is enabled
        if (!$this->isMailEnabled()) {
            return false;
        }
        
        // Check if the user has an email address
        if (!$user->email) {
            return false;
        }
        
        try {
            // Send the email
            Mail::to($user)->send(new RewardMail($user, $amount, $type, $additionalData));
            return true;
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Failed to send reward email: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send reward emails to multiple users
     *
     * @param array $users Array of User objects
     * @param int|float $amount The reward amount
     * @param string $type The reward type (coins, points, etc.)
     * @param array $additionalData Additional data to include in the email
     * @return int Number of emails sent successfully
     */
    public function sendRewardEmailToMany(array $users, $amount, $type = 'coins', array $additionalData = []): int
    {
        // Check if mail is enabled
        if (!$this->isMailEnabled()) {
            return 0;
        }
        
        $sentCount = 0;
        
        foreach ($users as $user) {
            if ($this->sendRewardEmail($user, $amount, $type, $additionalData)) {
                $sentCount++;
            }
        }
        
        return $sentCount;
    }
    
    /**
     * Send a custom email using a template
     *
     * @param User $user The user to send the email to
     * @param string $templateSlug The slug of the template to use
     * @param array $variables The variables to replace in the template
     * @return bool Whether the email was sent successfully
     */
    public function sendCustomEmail(User $user, string $templateSlug, array $variables = []): bool
    {
        // Check if mail is enabled
        if (!$this->isMailEnabled()) {
            return false;
        }
        
        // Check if the user has an email address
        if (!$user->email) {
            return false;
        }
        
        // Get the template
        $template = EmailTemplate::where('slug', $templateSlug)
            ->where('active', true)
            ->first();
            
        if (!$template) {
            \Log::error("Email template not found: {$templateSlug}");
            return false;
        }
        
        try {
            // Add common variables
            $allVariables = array_merge($variables, [
                'userName' => $user->name,
                'userEmail' => $user->email,
                'siteName' => config('app.name', ' RewardBazar'),
                'siteUrl' => url('/'),
                'loginUrl' => route('login'),
                'date' => now()->format('F j, Y'),
            ]);
            
            // Parse the template
            $subject = $template->parseSubject($allVariables);
            $content = $template->parseContent($allVariables);
            
            // Send the email
            Mail::send([], [], function ($message) use ($user, $subject, $content) {
                $message->to($user->email)
                        ->subject($subject)
                        ->setBody($content, 'text/html');
            });
            
            return true;
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Failed to send custom email: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send custom emails to multiple users
     *
     * @param array $users Array of User objects
     * @param string $templateSlug The slug of the template to use
     * @param array $variables The variables to replace in the template
     * @return int Number of emails sent successfully
     */
    public function sendCustomEmailToMany(array $users, string $templateSlug, array $variables = []): int
    {
        // Check if mail is enabled
        if (!$this->isMailEnabled()) {
            return 0;
        }
        
        $sentCount = 0;
        
        foreach ($users as $user) {
            if ($this->sendCustomEmail($user, $templateSlug, $variables)) {
                $sentCount++;
            }
        }
        
        return $sentCount;
    }
    
    /**
     * Check if mail is enabled in the settings
     *
     * @return bool
     */
    private function isMailEnabled(): bool
    {
        return (bool) Setting::get('mail_enabled', false);
    }

    /**
     * Send an email using a template from the database
     *
     * @param string $to Recipient email address
     * @param string $templateSlug The slug of the template to use
     * @param array $variables Variables to parse in the template
     * @return bool Whether the email was sent successfully
     */
    public function sendTemplateEmail(string $to, string $templateSlug, array $variables = []): bool
    {
        try {
            // Find the template
            $template = EmailTemplate::where('slug', $templateSlug)
                                    ->where('active', true)
                                    ->first();
            
            if (!$template) {
                Log::error("Email template not found or not active: {$templateSlug}");
                return false;
            }
            
            // Parse the subject and content with variables
            $subject = $this->parseVariables($template->subject, $variables);
            $content = $this->parseVariables($template->content, $variables);
            
            // Send the email
            Mail::to($to)->send(new RewardMail($subject, $content));
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email with template {$templateSlug}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Parse template variables in a string
     *
     * @param string $text The text to parse
     * @param array $variables The variables to replace
     * @return string The parsed text
     */
    private function parseVariables(string $text, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $text = str_replace("{{" . $key . "}}", $value, $text);
        }
        
        return $text;
    }
} 