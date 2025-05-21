@extends('admin.layouts.app')

@section('title', 'Add Email Template')
@section('header', 'Create New Email Template')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.email_templates.index') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
            &larr; Back to Email Templates
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('admin.email_templates.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Template Name:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="subject" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Email Subject:</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('subject') border-red-500 @enderror" required>
                @error('subject')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1 dark:text-gray-400">Use @{{variableName}} for dynamic content. Example: Hello @{{userName}}!</p>
            </div>
            
            <div class="mb-4">
                <label for="type" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Template Type:</label>
                <select name="type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('type') border-red-500 @enderror" required>
                    <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="reward" {{ old('type') == 'reward' ? 'selected' : '' }}>Reward</option>
                    <option value="notification" {{ old('type') == 'notification' ? 'selected' : '' }}>Notification</option>
                    <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>System</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="content" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Email Content (HTML):</label>
                <textarea name="content" id="content" rows="15" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('content') border-red-500 @enderror" required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1 dark:text-gray-400">Use @{{variableName}} for dynamic content. HTML is supported.</p>
            </div>
            
            <div class="mb-4">
                <label for="variables" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Available Variables (JSON):</label>
                <textarea name="variables" id="variables" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('variables') border-red-500 @enderror">{{ old('variables', '{"userName": "User\'s name", "userEmail": "User\'s email address"}') }}</textarea>
                @error('variables')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1 dark:text-gray-400">Define available variables in JSON format, with names and descriptions.</p>
            </div>
            
            <div class="mb-6">
                <label for="active" class="flex items-center">
                    <input type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }} class="mr-2">
                    <span class="text-gray-700 text-sm font-bold dark:text-gray-300">Active</span>
                </label>
                <p class="text-gray-500 text-xs mt-1 dark:text-gray-400">If checked, this template will be available for use</p>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create Template
                </button>
                <a href="{{ route('admin.email_templates.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple function to highlight and format the HTML content in the textarea
    document.addEventListener('DOMContentLoaded', function() {
        const contentTextarea = document.getElementById('content');
        const variablesTextarea = document.getElementById('variables');
        
        // Add basic syntax highlighting if needed
        // This is just a placeholder - you might want to use a proper code editor like CodeMirror
    });
</script>
@endpush 