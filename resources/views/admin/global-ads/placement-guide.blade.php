@extends('admin.layouts.app')

@section('title', 'Ad Placement Guide')
@section('header', 'Ad Placement Guide')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Native Banner Ad Placement Guide</h3>
            <p class="mt-1 text-sm text-gray-500">How to add native banner ads to specific pages and sections of your site.</p>
        </div>
        
        <div class="px-4 py-5 sm:p-6">
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900">Overview</h4>
                <p class="mt-2 text-gray-700">Unlike banner and social bar ads that have fixed positions, Native Banner ads can be placed anywhere in your pages. This guide explains how to place them in specific locations.</p>
            </div>
            
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900">Global Placement</h4>
                <p class="mt-2 text-gray-700">When you enable Native Banner ads in the Global Ads settings, they will be displayed at the top of the content area on all pages by default.</p>
                <div class="mt-4 p-3 bg-blue-50 rounded-md">
                    <h5 class="text-sm font-medium text-blue-800">Default Native Banner Location:</h5>
                    <p class="mt-1 text-sm text-blue-700">The default location is right before the main content on all pages.</p>
                </div>
            </div>
            
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900">Custom Placement in Template Files</h4>
                <p class="mt-2 text-gray-700">To add Native Banner ads to specific sections of your template files, you can use the following code snippet:</p>
                
                <div class="mt-4 p-3 bg-gray-50 rounded-md">
                    <pre class="text-xs font-mono overflow-x-auto p-2">@if(isset($adSettings) && $adSettings['global_ads_enabled'] && $adSettings['global_native_ad_enabled'] && !empty($adSettings['global_native_ad_code']))
    <div class="native-banner-custom my-4">
        <!-- Custom Native Banner placement -->
        {!! $adSettings['global_native_ad_code'] !!}
    </div>
@endif</pre>
                </div>
                
                <p class="mt-4 text-gray-700">Add this code to any of your Blade templates where you want the native banner to appear:</p>
                <ul class="mt-2 list-disc pl-5 space-y-2 text-gray-700">
                    <li>In specific page templates (<code>resources/views/pages/*.blade.php</code>)</li>
                    <li>Between sections of your content</li>
                    <li>In sidebar or widget areas</li>
                    <li>Before or after specific components</li>
                </ul>
            </div>
            
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900">Example: Adding to Specific Pages</h4>
                <p class="mt-2 text-gray-700">Here's how to add a native banner to a specific page template:</p>
                
                <div class="mt-4 p-3 bg-gray-50 rounded-md">
                    <h5 class="text-sm font-medium text-gray-700">Example in dashboard.blade.php:</h5>
                    <pre class="text-xs font-mono overflow-x-auto mt-2 p-2">@extends('layouts.app')

@section('content')
    <h1>User Dashboard</h1>
    
    <div class="user-stats mb-6">
        <!-- User stats content -->
    </div>
    
    @if(isset($adSettings) && $adSettings['global_ads_enabled'] && $adSettings['global_native_ad_enabled'] && !empty($adSettings['global_native_ad_code']))
        <div class="native-banner-dashboard my-4">
            {!! $adSettings['global_native_ad_code'] !!}
        </div>
    @endif
    
    <div class="user-actions mt-6">
        <!-- More content -->
    </div>
@endsection</pre>
                </div>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h4 class="text-md font-medium text-yellow-800 mb-2">⚠️ Important Notes:</h4>
                <ul class="list-disc pl-5 text-sm text-yellow-700 space-y-1">
                    <li>Each native banner requires both the script tag and its corresponding container div</li>
                    <li>The container div ID must match exactly what's in the script you get from Adsterra</li>
                    <li>To place multiple native banners on the same page, you need separate ad codes for each placement</li>
                    <li>Too many ads on one page may negatively impact user experience and page load time</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 