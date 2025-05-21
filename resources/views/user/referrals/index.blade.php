@extends('layouts.app')

@section('title', 'Referral Program')

@section('content')
<div class="container mx-auto px-4 py-20">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-purple-600 to-indigo-600">
                <h2 class="text-xl font-bold text-white">Referral Program</h2>
                <p class="mt-1 text-sm text-purple-100">Invite friends and earn rewards</p>
            </div>
            
            <!-- Referral Stats -->
            <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500">Total Referrals</h3>
                        <p class="mt-1 text-3xl font-semibold text-indigo-600">{{ $user->referral_count }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500">Reward Per Referral</h3>
                        <p class="mt-1 text-3xl font-semibold text-indigo-600">{{ number_format($referralRewardAmount) }} <span class="text-sm text-gray-500">coins</span></p>
                        @if($referralRewardPercentage > 0)
                            <p class="mt-1 text-sm text-gray-500">+ {{ $referralRewardPercentage }}% of referral's earnings</p>
                        @endif
                    </div>
                    
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500">Total Earnings</h3>
                        <p class="mt-1 text-3xl font-semibold text-indigo-600">{{ number_format($totalReferralEarnings) }} <span class="text-sm text-gray-500">coins</span></p>
                        <div class="mt-1 flex justify-between text-xs text-gray-500">
                            <span>Sign-up: {{ number_format($totalFixedReferralEarnings) }}</span>
                            <span>Commission: {{ number_format($totalPercentageReferralEarnings) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Referral Links -->
            <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Your Referral Links</h3>
                <p class="mt-1 text-sm text-gray-500">Share these links with friends to earn rewards when they sign up</p>
                
                <!-- Website Referral Link
                <div class="mt-4">
                    <h4 class="text-md font-medium text-gray-900">Website Referral Link</h4>
                    <div class="mt-2 flex flex-col sm:flex-row rounded-md shadow-sm">
                        <input type="text" readonly id="referral-link" value="{{ url('/register?ref=' . $user->referral_code) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
                        <button type="button" onclick="copyLink('referral-link')" class="mt-2 sm:mt-0 inline-flex items-center justify-center px-3 py-2 border border-l-0 sm:border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-500 sm:text-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            Copy
                        </button>
                    </div>
                </div> -->
                
                <!-- Telegram Referral Link -->
                <div class="mt-4">
                   <!--  <h4 class="text-md font-medium text-gray-900">Telegram Referral Link</h4> -->
                    <div class="mt-2 flex flex-col sm:flex-row rounded-md shadow-sm">
                        <input type="text" readonly id="telegram-referral-link" value="{{ $telegramReferralLink }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
                        <button type="button" onclick="copyLink('telegram-referral-link')" class="mt-2 sm:mt-0 inline-flex items-center justify-center px-3 py-2 border border-l-0 sm:border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-500 sm:text-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            Copy
                        </button>
                    </div>
                    
                    <!-- Link Instructions for Telegram -->
                    <div class="mt-2 p-3 bg-blue-50 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">How to use this link</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>1. Send this link to friends using Telegram or other apps</p>
                                    <p>2. When they click the link, they'll open our Telegram Mini App</p>
                                    <p>3. After they sign up, you'll earn {{ number_format($referralRewardAmount) }} coins</p>
                                    @if($referralRewardPercentage > 0)
                                        <p>Plus, you'll earn {{ $referralRewardPercentage }}% of all coins their referred friends earn from spins, videos, and shortlinks!</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h4 class="text-md font-medium text-gray-900">Your Referral Code</h4>
                    <p class="mt-1 text-sm text-gray-500">Share this code with friends</p>
                    <div class="mt-2 inline-flex items-center px-4 py-2 bg-indigo-100 text-indigo-800 text-lg font-mono font-bold rounded-md">
                        {{ $user->referral_code }}
                    </div>
                </div>
            </div>
            
            <!-- Referral List -->
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900">Your Referred Users</h3>
                <p class="mt-1 text-sm text-gray-500">People who signed up using your referral link</p>
                
                @if($referredUsers->count() > 0)
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Joined</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reward Earned</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($referredUsers as $referral)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <span class="text-indigo-700 font-medium">{{ substr($referral->name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $referral->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $referral->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @if($referral->has_rewards)
                                                    <span class="text-green-600 font-medium">{{ number_format($referral->total_rewards) }} coins</span>
                                                @else
                                                    <span class="text-gray-500">No reward</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            You haven't referred any users yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="mt-4 bg-gray-50 rounded-lg p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No referrals yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Share your referral link with friends to start earning rewards!</p>
                    </div>
                @endif
            </div>
            
            <!-- How It Works -->
            <div class="px-4 py-5 sm:p-6 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">How It Works</h3>
                <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <span class="text-xl font-bold">1</span>
                        </div>
                        <div class="ml-16">
                            <h4 class="text-lg font-medium text-gray-900">Share Your Link</h4>
                            <p class="mt-2 text-sm text-gray-500">Share your unique referral link with friends through social media, email, or messaging apps.</p>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <span class="text-xl font-bold">2</span>
                        </div>
                        <div class="ml-16">
                            <h4 class="text-lg font-medium text-gray-900">Friends Sign Up</h4>
                            <p class="mt-2 text-sm text-gray-500">When your friends click your link and create an account, they'll be connected to you as referrals.</p>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <span class="text-xl font-bold">3</span>
                        </div>
                        <div class="ml-16">
                            <h4 class="text-lg font-medium text-gray-900">Earn Rewards</h4>
                            <p class="mt-2 text-sm text-gray-500">You'll earn {{ number_format($referralRewardAmount) }} coins for each friend who signs up using your referral link!</p>
                            @if($referralRewardPercentage > 0)
                                <p class="mt-2 text-sm text-gray-500">Plus, you'll earn {{ $referralRewardPercentage }}% of all coins their referred friends earn from spins, videos, and shortlinks!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.mobile-nav')
@push('scripts')
<script>
    function copyLink(elementId) {
        var copyText = document.getElementById(elementId);
        
        // For Telegram links, ensure we have a clean URL
        if (elementId === 'telegram-referral-link') {
            var linkValue = copyText.value;
            
            // Make sure the URL is clean - just the basic format with startapp parameter
            var cleanUrl = linkValue;
            
            if (linkValue.includes('?')) {
                var baseUrl = linkValue.split('?')[0];
                var params = linkValue.split('?')[1].split('&');
                var startappParam = null;
                
                // Extract only the startapp parameter
                for (var i = 0; i < params.length; i++) {
                    if (params[i].startsWith('startapp=')) {
                        startappParam = params[i];
                        break;
                    }
                }
                
                // Reconstruct with just the startapp parameter
                if (startappParam) {
                    cleanUrl = baseUrl + '?' + startappParam;
                }
            }
            
            // Set the cleaned URL as the value
            copyText.value = cleanUrl;
        }
        
        try {
            // Modern clipboard API method
            navigator.clipboard.writeText(copyText.value)
                .then(() => {
                    showCopyFeedback(elementId);
                })
                .catch(err => {
                    // Fallback for older browsers
                    copyTextFallback(copyText);
                });
        } catch (err) {
            // Even older fallback
            copyTextFallback(copyText);
        }
    }
    
    function copyTextFallback(copyText) {
        // Select the text
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        
        // Copy to clipboard
        document.execCommand("copy");
        
        // Show feedback
        showCopyFeedback(copyText.id);
    }
    
    function showCopyFeedback(elementId) {
        // Get the button
        var button = document.querySelector(`button[onclick="copyLink('${elementId}')"]`);
        var originalText = button.innerHTML;
        
        // Change button text/style to show feedback
        button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Copied!
        `;
        button.classList.add('text-green-600');
        
        // Reset after 2 seconds
        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('text-green-600');
        }, 2000);
    }
    
    // Clean links on page load
    document.addEventListener('DOMContentLoaded', function() {
        cleanTelegramLink();
    });
    
    function cleanTelegramLink() {
        var telegramLink = document.getElementById('telegram-referral-link');
        if (telegramLink) {
            var linkValue = telegramLink.value;
            
            // Clean the link to keep only the startapp parameter
            if (linkValue.includes('?')) {
                var baseUrl = linkValue.split('?')[0];
                var params = linkValue.split('?')[1].split('&');
                var startappParam = null;
                
                // Extract only the startapp parameter
                for (var i = 0; i < params.length; i++) {
                    if (params[i].startsWith('startapp=')) {
                        startappParam = params[i];
                        break;
                    }
                }
                
                // Reconstruct with just the startapp parameter
                if (startappParam) {
                    telegramLink.value = baseUrl + '?' + startappParam;
                }
            }
        }
    }
</script>
@endpush

<style>


html.dark .text-gray-900 {
    color:rgb(255, 255, 255) !important;
}

html.dark .to-purple-50 {
  --tw-gradient-to: #1a1a1a;
}
html.dark .from-indigo-50 {
  --tw-gradient-from: #1a1a1a;
}



/* Mobile-specific styles */
@media (max-width: 640px) {
    /* Fix table display on small screens */
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    
    /* Fix the copy button on mobile */
    input#referral-link {
        border-radius: 0.375rem 0.375rem 0 0;
    }
    
    button[onclick="copyLink('referral-link')"] {
        border-radius: 0 0 0.375rem 0.375rem;
        border-left: 1px solid #e5e7eb;
        border-top: none;
    }
    
    /* Make the how-it-works section easier to read */
    .relative {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .relative:last-child {
        border-bottom: none;
    }
}
</style>
@endsection