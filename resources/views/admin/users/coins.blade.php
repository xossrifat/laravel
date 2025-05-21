@extends('admin.layouts.app')

@section('title', 'User Coin Management')
@section('header', 'Coin Management for {{ $user->name }}')

@section('content')
<div class="space-y-6">
    <!-- User Info -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">User Information</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <p class="mt-2">
                        <span class="text-lg font-semibold text-indigo-600">{{ number_format($user->coins) }}</span>
                        <span class="text-gray-500">coins available</span>
                    </p>
                </div>
                <div>
                    <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $user->is_banned ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $user->is_banned ? 'Banned' : 'Active' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Adjust Coins -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Adjust Coin Balance</h3>
            <p class="mt-1 text-sm text-gray-500">Add or remove coins from this user's account.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.users.update-coins', $user) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700">Action</label>
                        <select id="action" name="action" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="add">Add Coins</option>
                            <option value="subtract">Remove Coins</option>
                        </select>
                    </div>
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                        <input type="number" name="amount" id="amount" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                        <input type="text" name="reason" id="reason" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                </div>
                <div>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Balance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Transactions</h3>
            <p class="mt-1 text-sm text-gray-500">The user's most recent coin transactions.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $transaction['type'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="text-green-600">+{{ number_format($transaction['amount']) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction['date']->format('M d, Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No recent transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 