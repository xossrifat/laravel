@extends('layouts.app')

@section('title', 'Withdrawal Proof')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md dark:bg-gray-800 dark:text-white">
    <h2 class="text-2xl font-bold mb-4 text-center">Withdrawal Proof</h2>
    
    <!-- Navigation links -->
    <div class="flex justify-center mb-6 space-x-2 text-sm">
        @auth
            <a href="{{ route('withdraw.form') }}" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full font-semibold dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">Request</a>
            <a href="{{ route('withdraw.history') }}" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full font-semibold dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">History</a>
        @else
            <a href="{{ route('login') }}" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full font-semibold dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">Login</a>
            <a href="{{ route('register') }}" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full font-semibold dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">Register</a>
        @endauth
        <a href="{{ route('withdraw.proof') }}" class="px-3 py-1 bg-indigo-500 text-white rounded-full font-semibold">Proofs</a>
    </div>
    
    <p class="text-center text-gray-600 dark:text-gray-300 mb-6">Users who have successfully withdrawn from our platform</p>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border dark:border-gray-600">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="border px-4 py-2 dark:border-gray-600">User</th>
                    <th class="border px-4 py-2 dark:border-gray-600">Payment Method</th>
                    <th class="border px-4 py-2 dark:border-gray-600">Number</th>
                    <th class="border px-4 py-2 dark:border-gray-600">Amount</th>
                    <th class="border px-4 py-2 dark:border-gray-600">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($completedWithdrawals as $withdrawal)
                    <tr class="dark:border-gray-600">
                        <td class="border px-4 py-2 dark:border-gray-600">{{ $withdrawal->user->name }}</td>
                        <td class="border px-4 py-2 dark:border-gray-600">{{ $withdrawal->payment_method }}</td>
                        <td class="border px-4 py-2 dark:border-gray-600">
                            @php
                                $number = $withdrawal->payment_number;
                                $length = strlen($number);
                                if ($length > 8) {
                                    // Show first 5 and last 2 digits, mask the rest
                                    $maskedNumber = substr($number, 0, 5) . str_repeat('*', $length - 7) . substr($number, -2);
                                } else {
                                    // For shorter numbers, show first 2 and last 2
                                    $maskedNumber = substr($number, 0, 2) . str_repeat('*', $length - 4) . substr($number, -2);
                                }
                            @endphp
                            {{ $maskedNumber }}
                        </td>
                        <td class="border px-4 py-2 dark:border-gray-600">৳{{ number_format($withdrawal->amount, 2) }}</td>
                        <td class="border px-4 py-2 dark:border-gray-600">{{ $withdrawal->processed_at ? $withdrawal->processed_at->format('d M Y') : $withdrawal->updated_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 dark:border-gray-600">No withdrawal proofs found yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $completedWithdrawals->links() }}
    </div>

    <div class="mt-8 bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
        <h3 class="font-bold text-yellow-700 dark:text-yellow-300 mb-2">Why join our platform?</h3>
        <ul class="list-disc pl-5 text-sm text-yellow-700 dark:text-yellow-300">
            <li class="mb-1">Real withdrawals as shown above</li>
            <li class="mb-1">Fast processing within 24-48 hours</li>
            <li class="mb-1">Multiple payment methods accepted</li>
            <li class="mb-1">Low minimum withdrawal amount</li>
        </ul>
    </div>

    <div class="mt-6 text-center">
        @guest
            <a href="{{ route('welcome') }}" class="inline-block px-6 py-2 bg-purple-500 text-white font-semibold rounded-lg hover:bg-purple-600 transition duration-200 mr-2">
                Back to Home
            </a>
        @endguest
        <a href="{{ route('register') }}" class="inline-block px-6 py-2 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition duration-200">
            Join Now & Start Earning
        </a>
    </div>
    

</div>
@include('layouts.banner')
@endsection

@include('partials.mobile-nav') 