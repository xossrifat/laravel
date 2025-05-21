@extends('admin.layouts.app')

@section('title', 'Global Ads Management')
@section('header', 'Global Ads Management')

@section('content')
<div class="space-y-6">
    <!-- Global Ads Control -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Global Ads Settings</h3>
            <p class="mt-1 text-sm text-gray-500">Configure Adsterra ads that will be displayed throughout the website.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.global-ads.update') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Master On/Off Switch -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="global_ads_enabled" id="global_ads_enabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            {{ isset($settings['global_ads_enabled']) && $settings['global_ads_enabled'] ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="global_ads_enabled" class="font-medium text-gray-700">Enable Global Ads</label>
                        <p class="text-gray-500">When enabled, ads will be displayed throughout the website based on the settings below.</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-5">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Banner Ad</h4>
                    <div class="flex items-start mb-4">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="global_banner_ad_enabled" id="global_banner_ad_enabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                {{ isset($settings['global_banner_ad_enabled']) && $settings['global_banner_ad_enabled'] ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="global_banner_ad_enabled" class="font-medium text-gray-700">Enable Banner Ads</label>
                            <p class="text-gray-500">Display banner ads at the top of the website.</p>
                        </div>
                    </div>
                    <div>
                        <label for="global_banner_ad_code" class="block text-sm font-medium text-gray-700 mb-1">Banner Ad Code</label>
                        <textarea name="global_banner_ad_code" id="global_banner_ad_code" rows="4" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono"
                            placeholder="Paste your Adsterra banner ad code here">{{ $settings['global_banner_ad_code'] }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Paste your Adsterra banner ad code here. Your banner URL is: {{ $settings['adsterra_banner_url'] }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-5">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Native Banner Ad</h4>
                    <div class="flex items-start mb-4">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="global_native_ad_enabled" id="global_native_ad_enabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                {{ isset($settings['global_native_ad_enabled']) && $settings['global_native_ad_enabled'] ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="global_native_ad_enabled" class="font-medium text-gray-700">Enable Native Banner Ads</label>
                            <p class="text-gray-500">Native Banner ads can be placed anywhere in the page body.</p>
                            <a href="{{ route('admin.global-ads.placement-guide') }}" class="inline-block mt-1 text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-info-circle mr-1"></i> View Placement Guide
                            </a>
                        </div>
                    </div>
                    <div>
                        <label for="global_native_ad_code" class="block text-sm font-medium text-gray-700 mb-1">Native Banner Ad Code</label>
                        <textarea name="global_native_ad_code" id="global_native_ad_code" rows="6" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono"
                            placeholder="Paste your Adsterra native banner ad code here">{{ $settings['global_native_ad_code'] }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Paste the entire native banner code from Adsterra including both the <strong>script</strong> and <strong>div container</strong> tags.</p>
                        
                        <div class="mt-4 p-3 bg-yellow-50 rounded-md">
                            <h5 class="text-sm font-medium text-yellow-800">Example Native Banner Code Format:</h5>
                            <pre class="mt-1 text-xs text-gray-600 overflow-x-auto"><code>&lt;script async="async" data-cfasync="false" src="//pl12345678.example.com/1001234567890abcdef/invoke.js"&gt;&lt;/script&gt;
&lt;div id="container-1001234567890abcdef"&gt;&lt;/div&gt;</code></pre>
                            <p class="mt-2 text-xs text-yellow-700">Native banner customization options (widget layout, font size, etc.) should be configured in your Adsterra account before copying the code.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-5">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Social Bar Ad</h4>
                    <div class="flex items-start mb-4">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="global_social_ad_enabled" id="global_social_ad_enabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                {{ isset($settings['global_social_ad_enabled']) && $settings['global_social_ad_enabled'] ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="global_social_ad_enabled" class="font-medium text-gray-700">Enable Social Bar Ads</label>
                            <p class="text-gray-500">Display social bar ads at the bottom of the content area.</p>
                        </div>
                    </div>
                    <div>
                        <label for="global_social_ad_code" class="block text-sm font-medium text-gray-700 mb-1">Social Bar Ad Code</label>
                        <textarea name="global_social_ad_code" id="global_social_ad_code" rows="4" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono"
                            placeholder="Paste your Adsterra social bar ad code here">{{ $settings['global_social_ad_code'] }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Paste your Adsterra social bar ad code here. Your social bar URL is: {{ $settings['adsterra_social_url'] }}</p>
                    </div>
                </div>

                <!-- Left Sidebar Ad -->
                <div class="border-t border-gray-200 pt-5">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Left Sidebar Ad</h4>
                    <div class="flex items-start mb-4">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="left_sidebar_ad_enabled" id="left_sidebar_ad_enabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                {{ isset($settings['left_sidebar_ad_enabled']) && $settings['left_sidebar_ad_enabled'] ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="left_sidebar_ad_enabled" class="font-medium text-gray-700">Enable Left Sidebar Ads</label>
                            <p class="text-gray-500">Display banner ads on the left side of the home page.</p>
                        </div>
                    </div>
                    <div>
                        <label for="left_sidebar_ad_key" class="block text-sm font-medium text-gray-700 mb-1">Left Sidebar Ad Key</label>
                        <input type="text" name="left_sidebar_ad_key" id="left_sidebar_ad_key" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono"
                            placeholder="Your Adsterra ad key (e.g., d007ce8d8904632b8c54dbc4807c29a0)" 
                            value="{{ $settings['left_sidebar_ad_key'] ?? 'd007ce8d8904632b8c54dbc4807c29a0' }}">
                        
                        <div class="mt-3 p-3 bg-yellow-50 rounded-md">
                            <h5 class="text-sm font-medium text-yellow-800">How to get your Ad Key</h5>
                            <p class="mt-1 text-xs text-gray-600">Find your Adsterra ad key in your publisher dashboard. It's the alphanumeric code in your ad code - extract only the key value.</p>
                            <p class="mt-1 text-xs text-gray-600">Example: For <code>d007ce8d8904632b8c54dbc4807c29a0</code>, enter just that key.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar Ad -->
                <div class="border-t border-gray-200 pt-5">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Right Sidebar Ad</h4>
                    <div>
                        <label for="right_sidebar_ad_code" class="block text-sm font-medium text-gray-700 mb-1">Right Sidebar Ad Code</label>
                        <textarea name="right_sidebar_ad_code" id="right_sidebar_ad_code" rows="10" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono"
                            placeholder="Paste your complete ad code here">{{ $settings['right_sidebar_ad_code'] }}</textarea>
                        
                        <div class="mt-3 p-3 bg-yellow-50 rounded-md">
                            <h5 class="text-sm font-medium text-yellow-800">Ad Settings</h5>
                            <p class="mt-1 text-xs text-gray-600">Paste your complete ad code here. It will be displayed exactly as entered on the right side of the page.</p>
                            <p class="mt-1 text-xs text-gray-600">Example:<br>
                            <code>&lt;script type="text/javascript"&gt;<br>
                            atOptions = {<br>
                                'key' : 'd007ce8d8904632b8c54dbc4807c29a0',<br>
                                'format' : 'iframe',<br>
                                'height' : 60,<br>
                                'width' : 468,<br>
                                'params' : {}<br>
                            };<br>
                            &lt;/script&gt;<br>
                            &lt;script type="text/javascript" src="//www.highperformanceformat.com/d007ce8d8904632b8c54dbc4807c29a0/invoke.js"&gt;&lt;/script&gt;</code>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded-lg mt-4">
                    <h4 class="text-md font-medium text-gray-700 mb-2">⚠️ Important Notes:</h4>
                    <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                        <li>You need to get your ad codes from the Adsterra publisher dashboard</li>
                        <li>Copy the entire script tag provided by Adsterra for each ad format</li>
                        <li>The URLs configured in the "Watch & Earn" section are shown here for reference</li>
                        <li>Test ads on different devices to ensure they display correctly</li>
                    </ul>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Ad Settings
                    </button>
                    
                    <a href="{{ route('admin.global-ads.initialize') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-sync-alt mr-2"></i> Initialize Default Settings
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 