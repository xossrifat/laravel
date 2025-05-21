@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md dark:bg-gray-800 dark:text-white">
    <h2 class="text-2xl font-bold mb-4 text-center">Withdraw History</h2>

    <!-- Navigation links -->
    <div class="flex justify-center mb-6 space-x-2 text-sm">
        <a href="{{ route('withdraw.form') }}" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full font-semibold dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">Request</a>
        <a href="{{ route('withdraw.history') }}" class="px-3 py-1 bg-indigo-500 text-white rounded-full font-semibold">History</a>
        <a href="{{ route('withdraw.proof') }}" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full font-semibold dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">Proofs</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border dark:border-gray-600">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="border px-4 py-2 dark:border-gray-600">Payment Method</th>
                    <th class="border px-4 py-2 dark:border-gray-600">Payment Number</th>
                    <th class="border px-4 py-2 dark:border-gray-600">Amount</th>
                    <th class="border px-4 py-2 dark:border-gray-600">Status</th>
                    <th class="border px-4 py-2 dark:border-gray-600">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr class="dark:border-gray-600">
                        <td class="border px-4 py-2 dark:border-gray-600">{{ $req->payment_method }}</td>
                        <td class="border px-4 py-2 dark:border-gray-600">{{ $req->payment_number }}</td>
                        <td class="border px-4 py-2 dark:border-gray-600">{{ $req->amount }}</td>
                        <td class="border px-4 py-2 capitalize dark:border-gray-600">
                            <span class="px-2 py-1 rounded text-sm
                                @if($req->status == 'approved' || $req->status == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($req->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @endif">
                                {{ $req->status }}
                            </span>
                        </td>
                        <td class="border px-4 py-2 dark:border-gray-600">{{ $req->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 dark:border-gray-600">No history found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@include('layouts.banner')
@endsection
@include('partials.mobile-nav')