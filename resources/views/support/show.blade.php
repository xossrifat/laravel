@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-6 p-6 bg-white rounded-xl shadow-md">
    <div class="flex items-center mb-6">
        <div class="flex-shrink-0 mr-3">
            <span class="text-2xl">💬</span>
        </div>
        <div>
            <h2 class="text-2xl font-bold">Support Message</h2>
            <p class="text-gray-600">View your conversation with our support team.</p>
        </div>
    </div>

    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-lg font-semibold">{{ $message->subject }}</h3>
                <span class="text-sm text-gray-500">Sent on {{ $message->created_at->format('M d, Y g:i A') }}</span>
            </div>
            <div>
                @if($message->status === 'new')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        New
                    </span>
                @elseif($message->status === 'in_progress')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        In Progress
                    </span>
                @elseif($message->status === 'answered')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Answered
                    </span>
                @endif
            </div>
        </div>
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <p class="whitespace-pre-line">{{ $message->message }}</p>
        </div>
    </div>

    @if($message->admin_reply)
        <div class="mt-8 mb-6">
            <h3 class="text-lg font-semibold">Support Team Response</h3>
            <span class="text-sm text-gray-500">Replied on {{ $message->replied_at->format('M d, Y g:i A') }}</span>
            <div class="mt-2 p-4 bg-indigo-50 rounded-lg">
                <p class="whitespace-pre-line">{{ $message->admin_reply }}</p>
            </div>
        </div>
    @else
        <div class="mt-8 mb-6 p-4 bg-yellow-50 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm text-yellow-700">
                    Our support team will respond to your message as soon as possible. Thank you for your patience.
                </p>
            </div>
        </div>
    @endif

    <div class="flex mt-6">
        <a href="{{ route('support.history') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 mr-2">
            Back to Message History
        </a>
        <a href="{{ route('support.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            Send a New Message
        </a>
    </div>
</div>
@endsection 
@include('partials.mobile-nav')