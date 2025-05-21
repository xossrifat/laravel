@extends('admin.layouts.app')

@section('title', 'Earnings Analytics')
@section('header', 'Earnings Analytics')

@section('content')
<div class="space-y-6">
    <!-- Summary Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Coins Earned</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalCoinsEarned) }}</dd>
                <p class="mt-2 text-sm text-gray-500">Worth ৳{{ number_format($totalCoinsEarned / $coinRate, 2) }}</p>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Money Paid</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">৳{{ number_format($totalMoneyPaid, 2) }}</dd>
                <p class="mt-2 text-sm text-gray-500">Across all withdrawals</p>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Pending Amount</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">৳{{ number_format($pendingAmount, 2) }}</dd>
                <p class="mt-2 text-sm text-gray-500">Awaiting approval</p>
            </div>
        </div>
    </div>

    <!-- Monthly Earnings -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Monthly Earnings</h3>
            <p class="mt-1 text-sm text-gray-500">Withdrawal requests and payouts by month over the last year.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-80">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Month</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Requests</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Approved Amount</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Pending Amount</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Rejected Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($monthly as $data)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('F Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($data->count) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">৳{{ number_format($data->total_approved, 2) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">৳{{ number_format($data->total_pending, 2) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">৳{{ number_format($data->total_rejected, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-sm text-gray-500 text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Coin Rate Information -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Coin Rate Information</h3>
            <p class="mt-1 text-sm text-gray-500">Current conversion rate of coins to currency.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <i class="fas fa-coins text-blue-500 text-xl"></i>
                </div>
                <div class="ml-5">
                    <h4 class="text-lg font-medium text-gray-900">Current Coin Rate</h4>
                    <p class="text-gray-500">{{ number_format($coinRate) }} coins = ৳1</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-cog mr-2"></i>
                    Update Coin Rate
                </a>
            </div>
        </div>
    </div>

    <!-- Back to Dashboard -->
    <div class="mt-6">
        <a href="{{ route('admin.analytics.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Analytics Dashboard
        </a>
    </div>
</div>
@endsection 