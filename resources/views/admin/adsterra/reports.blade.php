@extends('admin.layouts.app')

@section('title', 'Adsterra Reports')
@section('header', 'Adsterra Reports')

@section('content')
<div class="space-y-6">
    <!-- Monthly Reports -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Monthly Reports</h3>
            <p class="mt-1 text-sm text-gray-500">View ad performance statistics by month.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Month</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Views</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Unique Viewers</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Coins Awarded</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Avg. Reward</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($monthlyStats as $stats)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">
                                {{ \Carbon\Carbon::createFromDate($stats->year, $stats->month, 1)->format('F Y') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ number_format($stats->total_views) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ number_format($stats->unique_viewers) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ number_format($stats->coins_awarded) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ number_format($stats->total_views > 0 ? $stats->coins_awarded / $stats->total_views : 0, 1) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-3 py-4 text-sm text-gray-500 text-center">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $monthlyStats->links() }}
            </div>
        </div>
    </div>

    <!-- Back to Dashboard -->
    <div class="mt-6">
        <a href="{{ route('admin.adsterra.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Adsterra Dashboard
        </a>
    </div>
</div>
@endsection 