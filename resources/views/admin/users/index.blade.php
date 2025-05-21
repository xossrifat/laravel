@extends('admin.layouts.app')

@section('title', 'User Management')
@section('header', 'User Management')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <p class="mt-2 text-sm text-gray-700">A list of all users in your application including their name, email, coins, and activity stats.</p>
    </div>
</div>

<div class="mt-8 flex flex-col">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Coins</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Spins</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Videos</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Referral Code</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Referred By</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($users as $user)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                {{ $user->name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <a href="{{ route('admin.users.coins', $user) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ number_format($user->coins) }}
                                </a>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <a href="{{ route('admin.users.spins', $user) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $user->spins_count }}
                                </a>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <a href="{{ route('admin.users.videos', $user) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $user->video_watches_count }}
                                </a>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-blue-100 text-blue-800">
                                    {{ $user->referral_code }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if($user->referrer)
                                    <a href="{{ route('admin.users.referrals', $user) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $user->referrer->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">None</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $user->is_banned ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $user->is_banned ? 'Banned' : 'Active' }}
                                </span>
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.users.referrals', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                        Referrals
                                    </a>
                                    <form action="{{ route('admin.users.toggle-ban', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-{{ $user->is_banned ? 'green' : 'red' }}-600 hover:text-{{ $user->is_banned ? 'green' : 'red' }}-900">
                                            {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection 