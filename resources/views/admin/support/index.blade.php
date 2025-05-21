@extends('admin.layouts.app')

@section('title', 'Support Center')
@section('header', 'Support Center')

@section('content')
<div class="space-y-6">
    <!-- Introduction -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-headset text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">User Support Messages</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Manage support requests from your users. Quick responses lead to happy users!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Support Messages -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Support Messages</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            @if(count($messages) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">User</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Subject</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($messages as $message)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                    {{ $message->name }}
                                    <span class="block text-xs text-gray-500">{{ $message->email }}</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ Str::limit($message->subject, 50) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y g:i A') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @if($message->status === 'new')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            New
                                        </span>
                                    @elseif($message->status === 'in_progress')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            In Progress
                                        </span>
                                    @elseif($message->status === 'answered')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Answered
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($message->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <a href="{{ route('admin.support.messages', ['user_id' => $message->user_id]) }}" class="text-indigo-600 hover:text-indigo-900">View all messages</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500">No support messages received yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Support Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">New Messages</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $messages->where('status', 'new')->count() }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">In Progress</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $messages->where('status', 'in_progress')->count() }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Answered</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $messages->where('status', 'answered')->count() }}</dd>
            </div>
        </div>
    </div>
</div>
@endsection 