@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-xl shadow-md">
    <div class="flex items-center mb-6">
        <div class="flex-shrink-0 mr-3">
            <span class="text-2xl">💬</span>
        </div>
        <div>
            <h2 class="text-2xl font-bold">Contact Support</h2>
            <p class="text-gray-600">We're here to help with any issues or questions you have.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('support.store') }}">
        @csrf
        <div class="mb-4">
            <label for="subject" class="block text-gray-700 font-medium mb-2">Subject</label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('subject') border-red-500 @enderror">
            @error('subject')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="message" class="block text-gray-700 font-medium mb-2">Your Message</label>
            <textarea name="message" id="message" rows="5" required
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
            @error('message')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('support.history') }}" class="text-indigo-600 hover:text-indigo-800">View my message history</a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                Send Message
            </button>
        </div>
    </form>
</div>
@endsection 
@include('partials.mobile-nav')