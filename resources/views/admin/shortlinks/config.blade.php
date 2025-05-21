@extends('admin.layouts.app')

@section('title', 'Shortlinks Configuration')
@section('header', 'Shortlinks Configuration')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Shortlinks Settings</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.shortlinks.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route('admin.shortlinks.config.update') }}">
            @csrf
            
            <div class="mb-6">
                <div class="bg-blue-50 text-blue-800 p-4 rounded-lg mb-6">
                    <p class="font-medium">Configure how the shortlinks feature works for your users.</p>
                    <p class="mt-2 text-sm">These settings affect all shortlinks across the platform.</p>
                </div>
                
                <div class="mb-4">
                    <label for="claim_timeout" class="block text-gray-700 text-sm font-bold mb-2">
                        Claim Countdown Timer (seconds)
                    </label>
                    <input 
                        type="number" 
                        name="claim_timeout" 
                        id="claim_timeout" 
                        min="5"
                        max="300"
                        value="{{ old('claim_timeout', $claimTimeout) }}" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('claim_timeout') border-red-500 @enderror"
                    >
                    @error('claim_timeout')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">
                        How long users must wait (in seconds) before they can claim coins. 
                        Minimum: 5, Maximum: 300 (5 minutes)
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="show_timer" 
                            id="show_timer" 
                            {{ old('show_timer', $showTimer) ? 'checked' : '' }}
                            class="mr-2"
                        >
                        <span class="text-gray-700 text-sm font-bold">Show Countdown Timer</span>
                    </label>
                    <p class="text-gray-500 text-xs mt-1 ml-6">
                        If checked, users will see the seconds counting down. If unchecked, they'll only see a disabled button until the waiting period is complete.
                    </p>
                </div>
                
                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 