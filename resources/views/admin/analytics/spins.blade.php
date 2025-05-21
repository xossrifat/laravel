@extends('admin.layouts.app')

@section('title', 'Spin Analytics')
@section('header', 'Spin Analytics')

@section('content')
<div class="space-y-6">
    <!-- Daily Spins -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Daily Spins</h3>
            <p class="mt-1 text-sm text-gray-500">Spins per day over the last 30 days.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-80">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Spins</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($daily as $data)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($data->count) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-3 py-4 text-sm text-gray-500 text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Spins -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Monthly Spins</h3>
            <p class="mt-1 text-sm text-gray-500">Spins and coins earned by month over the last year.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-80">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Month</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Spins</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Coins Earned</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Avg. Reward</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($monthly as $data)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('F Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($data->count) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($data->total_coins) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($data->count > 0 ? $data->total_coins / $data->count : 0, 1) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-sm text-gray-500 text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Reward Distribution -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Reward Distribution</h3>
            <p class="mt-1 text-sm text-gray-500">Frequency of different rewards being won.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-80">
                <!-- This would typically be a chart -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Reward</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Times Won</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Percentage</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @php 
                                $totalSpins = $rewardDistribution->sum('count'); 
                            @endphp
                            @forelse($rewardDistribution as $reward)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ $reward->reward->name ?? 'Unknown' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($reward->count) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format(($reward->count / max(1, $totalSpins)) * 100, 1) }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-sm text-gray-500 text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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