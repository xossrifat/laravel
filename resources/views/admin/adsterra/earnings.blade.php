@extends('admin.layouts.app')

@section('title', 'Adsterra Earnings')
@section('header', 'Adsterra Earnings')

@section('content')
<div class="space-y-6">
    <!-- Earnings Overview -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Earnings Today</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">৳{{ number_format($earnings['today']) }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Earnings This Week</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">৳{{ number_format($earnings['this_week']) }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Earnings This Month</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">৳{{ number_format($earnings['this_month']) }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Earnings</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">৳{{ number_format($earnings['total']) }}</dd>
            </div>
        </div>
    </div>

    <!-- Note about Setup -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Setup Adsterra Integration</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        To view real earnings data, connect your Adsterra account in the Watch & Earn settings.
                    </p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="text-sm">
                <p class="mb-2">To set up Adsterra integration:</p>
                <ol class="list-decimal pl-5 space-y-1">
                    <li>Create an account at <a href="https://adsterra.com/" target="_blank" class="text-blue-600 hover:text-blue-900">Adsterra</a></li>
                    <li>Set up your ad zones (Banner, Interstitial, Social Bar)</li>
                    <li>Copy your Publisher ID and Zone IDs</li>
                    <li>Add them in the <a href="{{ route('admin.watch-earn.index') }}" class="text-blue-600 hover:text-blue-900">Watch & Earn configuration</a></li>
                </ol>
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