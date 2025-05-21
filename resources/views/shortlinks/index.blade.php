@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-gray-900 min-h-screen">

    <!-- Page Content -->
    <div class="mb-10">
        <h2 class="text-2xl font-bold text-center text-white mb-6">Visit & Earn (Shortlinks)</h2>
    
    @if(session('success'))
            <div class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
            <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
        <!-- Stats Section -->
        @php
            $totalLinks = count($shortlinks);
            $claimedLinks = $shortlinks->where('already_claimed', true)->count();
            $remainingLinks = $totalLinks - $claimedLinks;
        @endphp
        
        <div class="bg-gray-800 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="bg-gray-700 rounded p-2">
                    <p class="text-gray-400 text-xs">Total Links</p>
                    <p class="text-white font-bold text-lg">{{ $totalLinks }}</p>
                </div>
                <div class="bg-gray-700 rounded p-2">
                    <p class="text-gray-400 text-xs">Claimed</p>
                    <p class="text-green-400 font-bold text-lg">{{ $claimedLinks }}</p>
                </div>
                <div class="bg-gray-700 rounded p-2">
                    <p class="text-gray-400 text-xs">Remaining</p>
                    <p class="text-blue-400 font-bold text-lg">{{ $remainingLinks }}</p>
                </div>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-2 mt-3">
                @php
                    $progressPercent = $totalLinks > 0 ? ($claimedLinks / $totalLinks) * 100 : 0;
                @endphp
                <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>
        
        <!-- Sort links so claimed are at the end -->
        @php
            $activeLinks = $shortlinks->where('already_claimed', false);
            $claimedLinks = $shortlinks->where('already_claimed', true);
            $sortedLinks = $activeLinks->merge($claimedLinks);
        @endphp
        
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
            @forelse($sortedLinks as $shortlink)
                <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-700 {{ $shortlink->already_claimed ? 'opacity-75' : '' }}">
                    <div class="p-4 flex flex-col justify-between h-full min-h-[200px]">
                        <div>
                            <h3 class="text-sm font-semibold text-white mb-2 text-center whitespace-normal min-h-[40px]">
                                {{ $shortlink->title }}
                            </h3>

                            <p class="mb-3 text-center">
                                <span class="text-xs text-gray-400">Reward:</span> 
                                <span class="font-bold text-green-400">{{ $shortlink->coins }} coins</span>
                            </p>

                    @if($shortlink->max_claims !== null)
                                <div class="mb-3">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-400">Claims:</span>
                                        <span class="text-gray-300">{{ $shortlink->claims_left }}/{{ $shortlink->max_claims }}</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-1.5 mt-1">
                                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ ($shortlink->claims_left / $shortlink->max_claims) * 100 }}%"></div>
                                    </div>
                                </div>
                    @endif
                        </div>

                        <div class="mt-auto">
                    @if($shortlink->already_claimed)
                                <div class="bg-gray-700 text-gray-300 py-2 px-4 rounded-lg text-center text-xs font-medium">
                            Claimed Today
                                </div>
                    @elseif($shortlink->max_claims !== null && $shortlink->claims_left <= 0)
                                <div class="bg-gray-700 text-gray-300 py-2 px-4 rounded-lg text-center text-xs font-medium">
                            Limit Reached
                                </div>
                    @else
                        <button
                                    onclick="openShortlinkModal('{{ $shortlink->id }}', '{{ $shortlink->title }}', '{{ $shortlink->url }}', '{{ $shortlink->coins }}', {{ $shortlink->requires_verification ? 'true' : 'false' }}, '{{ $shortlink->verification_code ?? '' }}')"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-200 transform active:scale-95 text-sm font-medium">
                            Visit & Earn
                        </button>
                                @if($shortlink->requires_verification)
                                    <p class="text-xs text-blue-400 mt-1 text-center flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                        Requires code verification
                                    </p>
                                @endif
                    @endif
                        </div>
                </div>
            </div>
            @empty
                    <div class="col-span-2 md:col-span-3 lg:col-span-3 text-center py-8 text-gray-400 bg-gray-800 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    <p>No shortlinks available at the moment. Please check back later.</p>
                </div>
            @endforelse
        </div>

    </div>
    @include('layouts.banner')
</div>

<!-- Include the mobile navigation bar (only visible on mobile) -->
@include('partials.mobile-nav')

<!-- Integrated Shortlink & Verification Modal -->
<div id="shortlinkModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full text-white border border-gray-700">
            <!-- Modal Header -->
            <div class="bg-indigo-700 px-4 py-3">
                <h3 class="text-lg font-medium text-white" id="modal-title"></h3>
                        </div>
                        
            <!-- Modal Content -->
            <div class="bg-gray-800 p-6">
                <!-- Reward Info -->
                <div class="mb-5 text-center">
                    <div class="text-gray-400">Reward:</div>
                    <div class="text-2xl font-bold text-green-400"><span id="reward-amount"></span> coins</div>
                        </div>
                        
                <!-- Verification Code Input Section -->
                <div id="verification-section" class="mb-5">
                    <div class="bg-blue-900 bg-opacity-30 border border-blue-800 rounded-lg p-4 mb-4">
                        <p class="text-blue-300">Please enter the verification code shown on the shortlink page to claim your reward</p>
                        </div>
                    
                    <div class="mb-4">
                        <label for="verification_code" class="block text-gray-300 text-sm font-medium mb-2">
                            Verification Code:
                        </label>
                        <input type="text" id="verification_code" 
                            class="shadow-inner appearance-none border rounded w-full py-3 px-4 bg-gray-700 text-white border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter code" autocomplete="off">
                    </div>
                    
                    <div id="verification-error" class="hidden text-red-400 text-sm mb-4"></div>
                </div>
                
                <!-- Link Open Section -->
                <div class="flex flex-col space-y-4">
                    <a id="open-link-button" href="#" target="_blank" rel="noopener" class="inline-flex justify-center items-center px-5 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors active:bg-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Open Link in New Tab
                    </a>
                    
                    <button id="claim-button" type="button" onclick="triggerClaimReward()" class="disabled:opacity-50 disabled:cursor-not-allowed inline-flex justify-center items-center px-5 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Verify & Claim Reward
                    </button>
                    
                    <!-- Timer display -->
                    <div id="timer-container" class="text-center mt-2 bg-gray-700 rounded-md p-2">
                        <p class="text-sm text-gray-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            You can claim after: <span id="timer-display" class="font-bold ml-1 text-white">0s</span>
                        </p>
                    </div>
                </div>
                </div>
                
            <!-- Modal Footer -->
            <div class="bg-gray-700 px-4 py-3 sm:px-6 flex justify-end">
                <button type="button" onclick="closeShortlinkModal()" class="inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-300 hover:bg-gray-700 focus:outline-none sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentShortlinkId = null;
    let countdownInterval = null;
    let requiresVerification = false;
    let verificationCode = "";
    
    // Get the configurable timeout value from the server (or default to 15)
    const claimTimeout = {{ $claimTimeout ?? 15 }};
    
    function openShortlinkModal(id, title, url, coins, needsVerification, code) {
        // Store shortlink information
        currentShortlinkId = id;
        requiresVerification = needsVerification;
        verificationCode = code;
        
        // Set modal content
        document.getElementById('modal-title').textContent = title;
        document.getElementById('reward-amount').textContent = coins;
        document.getElementById('open-link-button').href = url;
        
        // Show/hide verification section based on whether it requires verification
        if (requiresVerification) {
            document.getElementById('verification-section').classList.remove('hidden');
            document.getElementById('claim-button').disabled = true;
        } else {
            document.getElementById('verification-section').classList.add('hidden');
            document.getElementById('claim-button').disabled = false;
        }
        
        // Reset any previous verification errors
        document.getElementById('verification-error').classList.add('hidden');
        document.getElementById('verification-error').textContent = '';
        document.getElementById('verification_code').value = '';
        
        // Show the modal
        document.getElementById('shortlinkModal').classList.remove('hidden');
        
        // Initialize timer
        startTimer();
    }
    
    function closeShortlinkModal() {
        document.getElementById('shortlinkModal').classList.add('hidden');
        
        // Clear any timers
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
        
        // Reset variables
        currentShortlinkId = null;
        requiresVerification = false;
        verificationCode = "";
        
        // Reset button state
        const claimButton = document.getElementById('claim-button');
        if (claimButton) {
            claimButton.disabled = false;
            claimButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Verify & Claim Reward';
        }
    }
    
    // Helper function to trigger button click (in case the event listener isn't working)
    function triggerClaimReward() {
        const claimButton = document.getElementById('claim-button');
        if (claimButton && !claimButton.disabled) {
            // Check verification if needed
            if (requiresVerification) {
                const verificationInput = document.getElementById('verification_code');
                const enteredCode = verificationInput.value.trim();
                const errorElement = document.getElementById('verification-error');
                
                if (enteredCode === '') {
                    errorElement.textContent = 'Please enter the verification code';
                    errorElement.classList.remove('hidden');
                    return;
                }
                
                // Verify the code (case-insensitive comparison)
                if (enteredCode.toUpperCase() !== verificationCode.toUpperCase()) {
                    errorElement.textContent = 'Invalid verification code. Please check and try again.';
                    errorElement.classList.remove('hidden');
                    return;
                }
            }
            
            // Show loading animation
            claimButton.disabled = true;
            claimButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
            
            // Navigate to the complete URL directly
            setTimeout(function() {
                window.location.href = "{{ url('shortlinks/complete') }}/" + currentShortlinkId;
            }, 100);
        }
    }
    
    function startTimer() {
        // Reset any existing timer
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        
        let secondsLeft = claimTimeout;
        const timerDisplay = document.getElementById('timer-display');
        const timerContainer = document.getElementById('timer-container');
        const claimButton = document.getElementById('claim-button');
        
        // Initially disable claim button during countdown
        claimButton.disabled = true;
        
        // Show timer
        timerContainer.classList.remove('hidden');
        timerDisplay.textContent = secondsLeft + 's';
        
        countdownInterval = setInterval(() => {
            secondsLeft--;
            timerDisplay.textContent = secondsLeft + 's';
            
            if (secondsLeft <= 0) {
                clearInterval(countdownInterval);
                timerContainer.classList.add('hidden');
                
                // Only enable claim button directly if no verification is required
                if (!requiresVerification) {
                    claimButton.disabled = false;
                } else if (document.getElementById('verification_code').value.trim() !== '') {
                    // If verification is required but user already entered something, enable button
                    claimButton.disabled = false;
                }
            }
        }, 1000);
    }
    
    // Set up event listeners when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        const claimButton = document.getElementById('claim-button');
        const verificationInput = document.getElementById('verification_code');
        
        // Verify code when claim button is clicked
        claimButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default behavior
            
            // First check if the button is disabled
            if (this.disabled) {
                return false;
            }
            
            if (requiresVerification) {
                const enteredCode = verificationInput.value.trim();
                const errorElement = document.getElementById('verification-error');
                
                if (enteredCode === '') {
                    errorElement.textContent = 'Please enter the verification code';
                    errorElement.classList.remove('hidden');
                    return;
                }
                
                // Verify the code (case-insensitive comparison)
                if (enteredCode.toUpperCase() !== verificationCode.toUpperCase()) {
                    errorElement.textContent = 'Invalid verification code. Please check and try again.';
                    errorElement.classList.remove('hidden');
                    return;
                }
            }
            
            // Show loading animation
            claimButton.disabled = true; // Disable to prevent multiple clicks
            claimButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
            
            // Code is correct or verification not required - proceed with claim
            // Use setTimeout to ensure the UI updates before redirect
            setTimeout(function() {
                window.location.href = "{{ url('shortlinks/complete') }}/" + currentShortlinkId;
            }, 100);
        });
        
        // Enable claim button when verification code is entered (after timer expires)
        if (verificationInput) {
            verificationInput.addEventListener('input', function() {
                const timerContainer = document.getElementById('timer-container');
                
                // Only enable if timer has completed
                if (timerContainer.classList.contains('hidden')) {
                    claimButton.disabled = false;
                }
            });
        }
        
        // Mark verification link as visited when clicked
        document.getElementById('open-link-button').addEventListener('click', function() {
            this.classList.add('text-blue-300', 'border-blue-500');
            
            // Add vibration feedback on mobile
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        });
        
        // Add vibration feedback for buttons on mobile
        document.querySelectorAll('button:not([disabled])').forEach(button => {
            button.addEventListener('click', function() {
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            });
        });
    });
</script>

<style>
/* Mobile optimizations */
@media (max-width: 640px) {
    /* Prevent text selection on mobile devices */
    body {
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }
    
    /* Better button spacing on mobile */
    button, a.inline-flex {
        min-height: 44px; /* Apple's recommended minimum touch target size */
    }
    
    /* Make modal more mobile-friendly */
    #shortlinkModal .inline-block {
        width: 92%;
        max-width: 92%;
        margin: 0 4%;
    }
}



.font-semibold {
  font-weight: 663;
  text-align: center;
}
/* Add animation for pressed buttons */
button:active:not([disabled]), 
a.inline-flex:active {
    transform: scale(0.98);
}

/* Improve focus states for keyboard navigation */
button:focus, 
input:focus, 
a:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}

/* Card animations */
.grid > div {
    transition: all 0.2s ease;
}

.grid > div:active {
    transform: scale(0.98);
}

/* Gradient effect for progress bars */
.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

/* Custom scrollbar for dark theme */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: #1f2937;
}

::-webkit-scrollbar-thumb {
    background: #4f46e5;
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: #4338ca;
}
</style>
@endpush 