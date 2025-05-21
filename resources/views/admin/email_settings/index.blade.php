@extends('admin.layouts.app')

@section('title', 'Email Settings')
@section('header', 'Email Configuration')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">SMTP Configuration</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Configure your email server settings.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 dark:bg-green-900 dark:text-green-200 dark:border-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 dark:bg-red-900 dark:text-red-200 dark:border-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.email_settings.update') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="mail_enabled" id="mail_enabled" value="1" {{ $settings['mail_enabled'] ? 'checked' : '' }} class="mr-2">
                            <label for="mail_enabled" class="block text-gray-700 text-sm font-bold dark:text-gray-300">Enable Email Sending</label>
                        </div>
                        <p class="text-gray-500 text-xs mt-1 dark:text-gray-400">When enabled, the application will use these settings to send emails.</p>
                    </div>
                    
                    <div>
                        <label for="mail_mailer" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Mail Driver:</label>
                        <select name="mail_mailer" id="mail_mailer" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('mail_mailer') border-red-500 @enderror">
                            <option value="smtp" {{ $settings['mail_mailer'] == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ $settings['mail_mailer'] == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="mailgun" {{ $settings['mail_mailer'] == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ $settings['mail_mailer'] == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            <option value="postmark" {{ $settings['mail_mailer'] == 'postmark' ? 'selected' : '' }}>Postmark</option>
                            <option value="log" {{ $settings['mail_mailer'] == 'log' ? 'selected' : '' }}>Log</option>
                            <option value="array" {{ $settings['mail_mailer'] == 'array' ? 'selected' : '' }}>Array (Testing)</option>
                        </select>
                        @error('mail_mailer')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="mail_host" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">SMTP Host:</label>
                        <input type="text" name="mail_host" id="mail_host" value="{{ $settings['mail_host'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('mail_host') border-red-500 @enderror" placeholder="smtp.example.com">
                        @error('mail_host')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="mail_port" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">SMTP Port:</label>
                        <input type="number" name="mail_port" id="mail_port" value="{{ $settings['mail_port'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('mail_port') border-red-500 @enderror" placeholder="587">
                        @error('mail_port')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="mail_username" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">SMTP Username:</label>
                        <input type="text" name="mail_username" id="mail_username" value="{{ $settings['mail_username'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('mail_username') border-red-500 @enderror" placeholder="username@example.com">
                        @error('mail_username')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="mail_password" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">SMTP Password:</label>
                        <input type="password" name="mail_password" id="mail_password" value="{{ $settings['mail_password'] ? '********' : '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('mail_password') border-red-500 @enderror" placeholder="Leave empty to keep current password">
                        @error('mail_password')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1 dark:text-gray-400">Leave empty to keep the current password.</p>
                    </div>
                    
                    <div>
                        <label for="mail_encryption" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Encryption:</label>
                        <select name="mail_encryption" id="mail_encryption" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('mail_encryption') border-red-500 @enderror">
                            <option value="tls" {{ $settings['mail_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ $settings['mail_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ $settings['mail_encryption'] == null ? 'selected' : '' }}>None</option>
                        </select>
                        @error('mail_encryption')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="mail_from_address" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">From Address:</label>
                        <input type="email" name="mail_from_address" id="mail_from_address" value="{{ $settings['mail_from_address'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('mail_from_address') border-red-500 @enderror" placeholder="noreply@example.com">
                        @error('mail_from_address')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="mail_from_name" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">From Name:</label>
                        <input type="text" name="mail_from_name" id="mail_from_name" value="{{ $settings['mail_from_name'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('mail_from_name') border-red-500 @enderror" placeholder=" RewardBazar">
                        @error('mail_from_name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save Settings
                    </button>
                </div>
            </form>
            
            <div class="mt-12">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Test Email Configuration</h3>
                <form action="{{ route('admin.email_settings.test') }}" method="POST" class="max-w-md">
                    @csrf
                    <div class="mb-4">
                        <label for="test_email" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Send Test Email To:</label>
                        <input type="email" name="test_email" id="test_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('test_email') border-red-500 @enderror" placeholder="your@email.com" required>
                        @error('test_email')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Send Test Email
                    </button>
                    <p class="text-gray-500 text-xs mt-2 dark:text-gray-400">
                        Note: Make sure to save your settings before testing.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 