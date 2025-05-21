@extends('admin.layouts.app')

@section('title', 'User Messages')
@section('header', 'User Support Messages')

@section('content')
<div class="space-y-6">
    <!-- User Info -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-user-circle text-blue-500 text-3xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Thread -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Support Messages</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            @if(count($messages) > 0)
                <div class="space-y-6">
                    @foreach($messages as $message)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-md font-medium text-gray-900">{{ $message->subject }}</h4>
                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y g:i A') }}</span>
                                    @if($message->status === 'new')
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            New
                                        </span>
                                    @elseif($message->status === 'in_progress')
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            In Progress
                                        </span>
                                    @elseif($message->status === 'answered')
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Answered
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-700">
                                <p>{{ $message->message }}</p>
                            </div>
                            
                            @if($message->admin_reply)
                                <div class="mt-4 ml-8 bg-indigo-50 p-3 rounded-lg">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-reply text-indigo-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <span class="text-xs text-gray-500">
                                                Admin reply - {{ \Carbon\Carbon::parse($message->replied_at)->format('M d, Y g:i A') }}
                                            </span>
                                            <p class="text-sm text-gray-700 mt-1">{{ $message->admin_reply }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4">
                                    <form action="{{ route('admin.support.reply', $message->id) }}" method="POST">
                                        @csrf
                                        <div>
                                            <label for="reply" class="block text-sm font-medium text-gray-700">Reply to this message</label>
                                            <textarea id="reply" name="reply" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                        </div>
                                        <div class="mt-2 flex justify-end">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Send Reply
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">No messages found for this user.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('admin.support.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Support Center
        </a>
    </div>
</div>
@endsection 