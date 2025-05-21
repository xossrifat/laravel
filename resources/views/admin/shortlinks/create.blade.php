@extends('admin.layouts.app')

@section('title', 'Add New Shortlink')
@section('header', 'Add New Shortlink')

@php
use App\Models\Setting;
@endphp

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
    <form method="POST" action="{{ route('admin.shortlinks.store') }}">
        @csrf
        
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror" required>
            @error('title')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="url" class="block text-gray-700 text-sm font-bold mb-2">URL:</label>
            <input type="url" name="url" id="url" value="{{ old('url') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('url') border-red-500 @enderror" required>
            @error('url')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-xs mt-1">Enter the full URL including http:// or https://</p>
        </div>
        
        <div class="mb-4">
            <label for="coins" class="block text-gray-700 text-sm font-bold mb-2">Coins Reward:</label>
            <input type="number" name="coins" id="coins" value="{{ old('coins', 50) }}" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('coins') border-red-500 @enderror" required>
            @error('coins')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="timer_duration" class="block text-gray-700 text-sm font-bold mb-2">Timer Duration (seconds):</label>
            <input type="number" name="timer_duration" id="timer_duration" value="{{ old('timer_duration') }}" min="5" max="300" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('timer_duration') border-red-500 @enderror">
            @error('timer_duration')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-xs mt-1">Leave empty to use the global timer setting ({{ Setting::get('shortlink_claim_timeout', 15) }} seconds)</p>
        </div>
        
        <div class="mb-4">
            <label for="max_claims" class="block text-gray-700 text-sm font-bold mb-2">Max Claims (users):</label>
            <input type="number" name="max_claims" id="max_claims" value="{{ old('max_claims') }}" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('max_claims') border-red-500 @enderror">
            @error('max_claims')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-xs mt-1">Leave empty for unlimited claims. If set, only this many users can claim the link.</p>
        </div>
        
        <!-- Verification Code Section -->
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-bold text-blue-700 mb-2">Code Verification Settings</h3>
            
            <div class="mb-4">
                <label for="requires_verification" class="flex items-center">
                    <input type="checkbox" name="requires_verification" id="requires_verification" value="1" {{ old('requires_verification', false) ? 'checked' : '' }} class="mr-2" onchange="toggleVerificationFields()">
                    <span class="text-gray-700 text-sm font-bold">Require Code Verification</span>
                </label>
                <p class="text-gray-500 text-xs mt-1">If checked, users must enter a verification code after visiting the link to claim rewards.</p>
            </div>
            
            <div id="verification_code_container" class="mb-4 {{ old('requires_verification') ? '' : 'hidden' }}">
                <label for="verification_code" class="block text-gray-700 text-sm font-bold mb-2">Verification Code:</label>
                <input type="text" name="verification_code" id="verification_code" value="{{ old('verification_code') }}" maxlength="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('verification_code') border-red-500 @enderror">
                @error('verification_code')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Enter a code or leave blank to auto-generate. This code will be shown to users when they visit the link.</p>
                
                <div class="flex mt-2">
                    <button type="button" onclick="generateCode()" class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-1 px-2 rounded">
                        Generate Random Code
                    </button>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <label for="active" class="flex items-center">
                <input type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }} class="mr-2">
                <span class="text-gray-700 text-sm font-bold">Active</span>
            </label>
            <p class="text-gray-500 text-xs mt-1">If checked, the shortlink will be visible to users</p>
        </div>
        
        <div class="mb-4">
            <label for="rewarded" class="flex items-center">
                <input type="checkbox" name="rewarded" id="rewarded" value="1" {{ old('rewarded', false) ? 'checked' : '' }} class="mr-2">
                <span class="text-gray-700 text-sm font-bold">Send Reward Email</span>
            </label>
            <p class="text-gray-500 text-xs mt-1">If checked, users will receive a reward notification email when they complete this shortlink and when the shortlink is updated.</p>
        </div>
        
        <div class="mb-6">
            <label for="daily_reset" class="flex items-center">
                <input type="checkbox" name="daily_reset" id="daily_reset" value="1" {{ old('daily_reset', false) ? 'checked' : '' }} class="mr-2">
                <span class="text-gray-700 text-sm font-bold">Daily Reset</span>
            </label>
            <p class="text-gray-500 text-xs mt-1">If checked, users can claim this shortlink once per day. If unchecked, they can only claim it once ever.</p>
        </div>
        
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add Shortlink
            </button>
            <a href="{{ route('admin.shortlinks.index') }}" class="text-gray-500 hover:text-gray-700">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function toggleVerificationFields() {
        const requiresVerification = document.getElementById('requires_verification').checked;
        const codeContainer = document.getElementById('verification_code_container');
        
        if (requiresVerification) {
            codeContainer.classList.remove('hidden');
        } else {
            codeContainer.classList.add('hidden');
        }
    }
    
    function generateCode() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Removed similar-looking characters
        let code = '';
        for (let i = 0; i < 6; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('verification_code').value = code;
    }
</script>
@endsection 