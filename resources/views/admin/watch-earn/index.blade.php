@extends('admin.layouts.app')

@section('title', 'Watch & Earn Configuration')
@section('header', 'Watch & Earn Configuration')

@section('content')
<div class="space-y-6">
    <!-- Ad Provider Selection -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Ad Provider</h3>
            <p class="mt-1 text-sm text-gray-500">Choose which ad provider to use for video advertisements.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.watch-earn.config.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Ad Provider</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="ad_provider" value="unity" class="form-radio" {{ isset($settings['ad_provider']) && $settings['ad_provider'] == 'unity' ? 'checked' : '' }}>
                                <span class="ml-2">Unity Ads</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="ad_provider" value="adsterra" class="form-radio" {{ !isset($settings['ad_provider']) || $settings['ad_provider'] == 'adsterra' ? 'checked' : '' }}>
                                <span class="ml-2">Adsterra</span>
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Ad Provider
                </button>
            </form>
        </div>
    </div>

    <!-- Adsterra Ad Formats Configuration -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Adsterra Ad Formats & URLs</h3>
            <p class="mt-1 text-sm text-gray-500">Configure your Adsterra ad formats and corresponding URLs.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.watch-earn.adsterra.update') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Ad Format Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Default Ad Format</label>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <label class="inline-flex items-center p-3 border border-gray-300 rounded-md {{ $settings['adsterra_format'] == 'popunder' ? 'bg-indigo-50 border-indigo-300' : '' }}">
                            <input type="radio" name="adsterra_format" value="popunder" class="form-radio" {{ $settings['adsterra_format'] == 'popunder' ? 'checked' : '' }}>
                            <span class="ml-2">Popunder</span>
                        </label>
                        <label class="inline-flex items-center p-3 border border-gray-300 rounded-md {{ $settings['adsterra_format'] == 'native' ? 'bg-indigo-50 border-indigo-300' : '' }}">
                            <input type="radio" name="adsterra_format" value="native" class="form-radio" {{ $settings['adsterra_format'] == 'native' ? 'checked' : '' }}>
                            <span class="ml-2">Native Banner</span>
                        </label>
                        <label class="inline-flex items-center p-3 border border-gray-300 rounded-md {{ $settings['adsterra_format'] == 'banner' ? 'bg-indigo-50 border-indigo-300' : '' }}">
                            <input type="radio" name="adsterra_format" value="banner" class="form-radio" {{ $settings['adsterra_format'] == 'banner' ? 'checked' : '' }}>
                            <span class="ml-2">Banner</span>
                        </label>
                        <label class="inline-flex items-center p-3 border border-gray-300 rounded-md {{ $settings['adsterra_format'] == 'social' ? 'bg-indigo-50 border-indigo-300' : '' }}">
                            <input type="radio" name="adsterra_format" value="social" class="form-radio" {{ $settings['adsterra_format'] == 'social' ? 'checked' : '' }}>
                            <span class="ml-2">Social Bar</span>
                        </label>
                    </div>
                </div>

                <!-- Ad URLs -->
                <div class="border-t border-gray-200 pt-5">
                    <h4 class="text-md font-medium text-gray-700 mb-3">Ad URLs for Each Format</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="adsterra_default_url" class="block text-sm font-medium text-gray-700 mb-1">Default Ad URL (Required)</label>
                            <input type="url" name="adsterra_default_url" id="adsterra_default_url" 
                                value="{{ $settings['adsterra_default_url'] }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="https://www.profitableratecpm.com/..." required>
                            <p class="mt-1 text-xs text-gray-500">This URL will be used if a specific format URL is not provided</p>
                        </div>
                        
                        <div>
                            <label for="adsterra_popunder_url" class="block text-sm font-medium text-gray-700 mb-1">Popunder URL</label>
                            <input type="url" name="adsterra_popunder_url" id="adsterra_popunder_url" 
                                value="{{ $settings['adsterra_popunder_url'] }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="https://www.profitableratecpm.com/...">
                        </div>
                        
                        <div>
                            <label for="adsterra_native_url" class="block text-sm font-medium text-gray-700 mb-1">Native Banner URL</label>
                            <input type="url" name="adsterra_native_url" id="adsterra_native_url" 
                                value="{{ $settings['adsterra_native_url'] }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="https://www.profitableratecpm.com/...">
                        </div>
                        
                        <div>
                            <label for="adsterra_banner_url" class="block text-sm font-medium text-gray-700 mb-1">Banner URL</label>
                            <input type="url" name="adsterra_banner_url" id="adsterra_banner_url" 
                                value="{{ $settings['adsterra_banner_url'] }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="https://www.profitableratecpm.com/...">
                        </div>
                        
                        <div>
                            <label for="adsterra_social_url" class="block text-sm font-medium text-gray-700 mb-1">Social Bar URL</label>
                            <input type="url" name="adsterra_social_url" id="adsterra_social_url" 
                                value="{{ $settings['adsterra_social_url'] }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="https://www.profitableratecpm.com/...">
                        </div>
                        
                        <div>
                            <label for="video_ad_url" class="block text-sm font-medium text-gray-700 mb-1">Video Ad URL</label>
                            <input type="url" name="video_ad_url" id="video_ad_url" 
                                value="{{ $settings['video_ad_url'] }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="https://www.profitableratecpm.com/...">
                            <p class="mt-1 text-xs text-gray-500">This URL will be used specifically for the video ad page</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Adsterra Settings
                </button>
            </form>
        </div>
    </div>

    <!-- Video Ads Management -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Video Ads Management</h3>
            <p class="mt-1 text-sm text-gray-500">Add and manage multiple video ad sources with priority settings.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.watch-earn.video-ads.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">Ad Name</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Example: Adsterra Social Bar" required>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="type" class="block text-sm font-medium text-gray-700">Ad Type</label>
                        <div class="mt-1">
                            <select name="type" id="type" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="url">URL (iframe-based)</option>
                                <option value="script">Script (function call)</option>
                            </select>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">URL: redirects to site, Script: executes JS function</p>
                    </div>

                    <div class="sm:col-span-5">
                        <label for="content" class="block text-sm font-medium text-gray-700">Ad Content</label>
                        <div class="mt-1">
                            <textarea name="content" id="content" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required placeholder="For URL: https://example.com/ad&#10;For script: show_9341414()"></textarea>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">For URL type: Enter full URL. For script type: Enter function call like show_9341414()</p>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                        <div class="mt-1">
                            <input type="number" name="priority" id="priority" min="1" max="100" value="10" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Higher = more likely to appear</p>
                    </div>

                    <div class="sm:col-span-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="is_active" id="is_active" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700">Active</label>
                                <p class="text-gray-500">Enable this ad for display to users</p>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Video Ad
                </button>
            </form>

            <!-- Existing Video Ads -->
            @if(isset($videoAds) && $videoAds->count() > 0)
                <div class="mt-6">
                    <h4 class="text-lg font-medium text-gray-900">Configured Video Ads</h4>
                    <p class="mt-1 text-sm text-gray-500">
                        Total ads: {{ $videoAds->count() }} | 
                        Total priority: {{ $totalPriority }}
                    </p>
                    
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chance</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($videoAds as $ad)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ad->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ad->type === 'url' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ strtoupper($ad->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ad->priority }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($totalPriority > 0)
                                            {{ round(($ad->priority / $totalPriority) * 100, 1) }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ad->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $ad->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <form action="{{ route('admin.watch-earn.video-ads.toggle', ['videoAdFixed' => $ad->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">{{ $ad->is_active ? 'Disable' : 'Enable' }}</button>
                                            </form>
                                            
                                            <span class="text-gray-300">|</span>
                                            
                                            <button type="button" class="text-indigo-600 hover:text-indigo-900 edit-ad-btn" 
                                                    data-id="{{ $ad->id }}" 
                                                    data-name="{{ $ad->name }}"
                                                    data-type="{{ $ad->type }}"
                                                    data-priority="{{ $ad->priority }}"
                                                    data-active="{{ $ad->is_active ? 1 : 0 }}"
                                                    data-content="{{ htmlspecialchars($ad->content) }}">
                                                Edit
                                            </button>
                                            
                                            <span class="text-gray-300">|</span>
                                            
                                            <form action="{{ route('admin.watch-earn.video-ads.delete', ['videoAdFixed' => $ad->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this ad?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">No video ads configured</h3>
                            <p class="mt-2 text-sm text-yellow-700">
                                Add at least one video ad above to start showing ads to users. Until then, the system will use legacy settings.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Edit Ad Modal -->
    <div id="editAdModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Video Ad</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <form id="editAdForm" method="POST">
                    @csrf
                    {{-- Laravel requires method spoofing for PUT requests --}}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="edit_name" class="block text-sm font-medium text-gray-700">Ad Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="edit_name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="edit_type" class="block text-sm font-medium text-gray-700">Ad Type</label>
                            <div class="mt-1">
                                <select name="type" id="edit_type" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="url">URL (iframe-based)</option>
                                    <option value="script">Script (function call)</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-5">
                            <label for="edit_content" class="block text-sm font-medium text-gray-700">Ad Content</label>
                            <div class="mt-1">
                                <textarea name="content" id="edit_content" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <label for="edit_priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <div class="mt-1">
                                <input type="number" name="priority" id="edit_priority" min="1" max="100" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="is_active" id="edit_is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="edit_is_active" class="font-medium text-gray-700">Active</label>
                                    <p class="text-gray-500">Enable this ad for display to users</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="button" id="cancelEditBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1">
                            Cancel
                        </button>
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Unity Ads Configuration -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Unity Ads Configuration</h3>
            <p class="mt-1 text-sm text-gray-500">Configure your Unity Ads credentials for video advertisements.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.watch-earn.config.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="ad_provider" value="{{ $settings['ad_provider'] }}">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="unity_game_id" class="block text-sm font-medium text-gray-700">Unity Game ID</label>
                        <input type="text" name="unity_game_id" id="unity_game_id" value="{{ $settings['unity_game_id'] ?? '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="unity_placement_id" class="block text-sm font-medium text-gray-700">Unity Placement ID (Interstitial)</label>
                        <input type="text" name="unity_placement_id" id="unity_placement_id" value="{{ $settings['unity_placement_id'] ?? '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="unity_test_mode" class="flex items-center text-sm font-medium text-gray-700">
                            <input type="checkbox" name="unity_test_mode" id="unity_test_mode" 
                                {{ isset($settings['unity_test_mode']) && $settings['unity_test_mode'] ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2">Enable Test Mode</span>
                        </label>
                    </div>
                    <div>
                        <label for="allow_ad_fallback" class="flex items-center text-sm font-medium text-gray-700">
                            <input type="checkbox" name="allow_ad_fallback" id="allow_ad_fallback" 
                                {{ isset($settings['allow_ad_fallback']) && $settings['allow_ad_fallback'] ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2">Allow Fallback Option (when ads fail to load)</span>
                        </label>
                    </div>
                </div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Unity Ads Configuration
                </button>
            </form>
        </div>
    </div>

    <!-- Video Rewards -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Video Rewards</h3>
            <p class="mt-1 text-sm text-gray-500">Set the number of coins users earn for watching a video.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.watch-earn.rewards.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="max-w-xs">
                    <label for="video_watch_reward" class="block text-sm font-medium text-gray-700">Coins per Video</label>
                    <input type="number" name="video_watch_reward" id="video_watch_reward" value="{{ $settings['video_watch_reward'] }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        min="1" required>
                </div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Video Rewards
                </button>
            </form>
        </div>
    </div>

    <!-- Daily Limits and Viewing Time -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Daily Video Limit & Viewing Time</h3>
            <p class="mt-1 text-sm text-gray-500">Set the maximum number of videos a user can watch per day and required viewing time.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.watch-earn.limits.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                    <label for="daily_video_limit" class="block text-sm font-medium text-gray-700">Daily Limit</label>
                    <input type="number" name="daily_video_limit" id="daily_video_limit" value="{{ $settings['daily_video_limit'] }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        min="1" required>
                    </div>
                    <div>
                        <label for="ad_view_time" class="block text-sm font-medium text-gray-700">Required Viewing Time (seconds)</label>
                        <input type="number" name="ad_view_time" id="ad_view_time" value="{{ $settings['ad_view_time'] ?? 10 }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            min="5" max="60" required>
                        <p class="mt-1 text-xs text-gray-500">How many seconds a user must watch the ad (5-60 seconds)</p>
                    </div>
                </div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Limits
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal elements
        const editModal = document.getElementById('editAdModal');
        const editForm = document.getElementById('editAdForm');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        
        // Get all edit buttons
        const editButtons = document.querySelectorAll('.edit-ad-btn');
        
        // Add click listeners to all edit buttons
        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Get ad data from data attributes
                const adId = button.dataset.id;
                const name = button.dataset.name;
                const type = button.dataset.type;
                const priority = button.dataset.priority;
                const isActive = button.dataset.active === '1';
                const content = button.dataset.content;
                
                // Set form action URL - using proper named route convention
                editForm.action = "{{ url('admin/watch-earn/video-ads') }}/" + adId + "?_method=PUT";
                
                // Populate form fields
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_type').value = type;
                document.getElementById('edit_priority').value = priority;
                document.getElementById('edit_is_active').checked = isActive;
                document.getElementById('edit_content').value = content;
                
                // Show modal
                editModal.classList.remove('hidden');
            });
        });
        
        // Close modal on cancel button click
        cancelEditBtn.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });
        
        // Close modal when clicking outside
        editModal.addEventListener('click', (e) => {
            if (e.target === editModal) {
                editModal.classList.add('hidden');
            }
        });
    });
</script>
@endpush 