@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-6 p-4 sm:p-6 bg-white rounded-xl shadow-md text-center dark:bg-gray-800 dark:text-white">
    <h2 class="text-xl sm:text-2xl font-bold mb-4">📺 Watch Ads & Earn Coins</h2>
    
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg dark:bg-green-900 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg dark:bg-red-900 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="mb-6 p-4 bg-blue-50 rounded-lg dark:bg-blue-900 dark:text-blue-100">
        <div class="flex flex-col justify-center items-center">
            <div class="text-4xl mb-2">💎</div>
            <p class="text-gray-700 dark:text-gray-200 text-lg font-medium">Earn <span class="font-bold text-blue-600 dark:text-blue-400">{{ $coinReward }}</span> coins per ad</p>
        </div>
        
        <div class="mt-4 bg-white dark:bg-gray-700 rounded-lg p-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-700 dark:text-gray-300">Watches left today:</span>
                <span class="font-bold text-blue-600 dark:text-blue-400 text-xl">{{ $watchesLeft }}</span>
            </div>
            
            <div class="w-full bg-gray-200 dark:bg-gray-600 h-3 mt-2 rounded-full overflow-hidden">
                @php
                    // Fix undefined variable by checking if $maxAds exists and providing a default
                    $totalAds = isset($maxAds) ? $maxAds : 10; // Default to 10 if not defined
                    $watchPercentage = $watchesLeft > 0 ? 
                        max(0, min(100, 100 - (($watchesLeft / $totalAds) * 100))) : 0;
                @endphp
                <div class="bg-blue-500 h-3" style="width: {{ $watchPercentage }}%"></div>
            </div>
        </div>
    </div>
    
    <p class="mb-6 text-gray-600 dark:text-gray-400">Tap the button below to watch an ad and earn coins.</p>

    @if($watchesLeft > 0)
        <div id="debug-info" class="mb-4 p-4 bg-gray-100 text-gray-700 rounded-lg text-left dark:bg-gray-700 dark:text-gray-300 hidden">
            <p class="font-bold">Debug Info:</p>
            <div id="debug-content" class="mt-2 text-xs"></div>
        </div>
        
        <form id="ad-form" action="{{ route('ads.watch') }}" method="POST" class="mt-4">
        @csrf
            <input type="hidden" id="ad-completed" name="ad_completed" value="0">
            <input type="hidden" id="fallback-used" name="fallback_used" value="0">
            @if(isset($adSettings['adId']))
            <input type="hidden" id="ad-id" name="ad_id" value="{{ $adSettings['adId'] }}">
            @endif
            <button id="watch-button" type="button" class="bg-pink-500 hover:bg-pink-600 text-white px-10 py-4 rounded-full text-lg font-bold w-full sm:w-auto animate-pulse transform active:scale-95 transition-transform">
                <span class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                    </svg>
                Watch Ad
                </span>
            </button>
            <button id="submit-button" type="submit" class="hidden bg-green-500 hover:bg-green-600 text-white px-6 py-4 rounded-full text-lg font-bold w-full sm:w-auto">
                <span class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                🎁 Claim Your Reward
                </span>
        </button>
    </form>

        <!-- Fallback option if ads don't load -->
        @if(isset($allowFallback) && $allowFallback)
        <div id="ad-error-container" class="mt-6 p-4 bg-yellow-100 text-yellow-700 rounded-lg dark:bg-yellow-900 dark:text-yellow-200 hidden">
            <p><strong>Having trouble loading ads?</strong></p>
            <p class="mt-2">You can still earn coins by clicking the button below:</p>
            <button id="fallback-button" class="mt-3 bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-full transform active:scale-95 transition-transform w-full">
                🎮 Try Alternative Method
            </button>
        </div>
        @endif
    @else
        <div class="mt-6 px-4 py-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex flex-col items-center">
            <div class="text-4xl mb-4">😴</div>
            <p class="text-gray-600 dark:text-gray-400 mb-3">You've watched all your ads for today.</p>
            <button disabled class="bg-gray-400 text-white px-6 py-3 rounded-full text-lg cursor-not-allowed opacity-70">
                No Watches Left Today
            </button>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Come back tomorrow for more!</p>
        </div>
    @endif
    @include('layouts.banner')
</div>

@if($watchesLeft > 0)
<script>
    // Helper for logging to page and console
    function debugLog(message) {
        console.log(message);
        const debugContent = document.getElementById('debug-content');
        if (debugContent) {
            const timestamp = new Date().toLocaleTimeString();
            debugContent.innerHTML += `<div>[${timestamp}] ${message}</div>`;
        }
    }

    // Main implementation
    document.addEventListener('DOMContentLoaded', function() {
        debugLog('Page loaded, setting up ad integration');
        
        const watchButton = document.getElementById('watch-button');
        const submitButton = document.getElementById('submit-button');
        const adCompletedInput = document.getElementById('ad-completed');
        const fallbackUsedInput = document.getElementById('fallback-used');
        const fallbackButton = document.getElementById('fallback-button');
        const adErrorContainer = document.getElementById('ad-error-container');
        
        // Watch button handler - show ad
        watchButton.addEventListener('click', function() {
            debugLog('Watch button clicked - showing ad');
            
            // Vibrate on mobile if supported for better feedback
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
            
            // Hide watch button
            watchButton.classList.add('hidden');
            
            try {
                @if(isset($adSettings['isLegacy']) && $adSettings['isLegacy'] === true)
                    // Legacy ad settings handling for backward compatibility
                    @if(isset($adSettings['videoAdUrl']) && !empty($adSettings['videoAdUrl']))
                        debugLog('Using legacy video ad URL');
                        showLegacyAd('{{ $adSettings['videoAdUrl'] }}');
                    @else
                        debugLog('No legacy video ad URL configured - using fallback');
                        handleAdError();
                    @endif
                @else
                    // New ad system based on VideoAd model
                    @if(isset($adSettings['type']) && $adSettings['type'] === 'script')
                        debugLog('Showing script-based ad');
                        showScriptAd(`{{ $adSettings['content'] }}`);
                    @elseif(isset($adSettings['type']) && $adSettings['type'] === 'url')
                        debugLog('Showing URL-based ad');
                        showUrlAd('{{ $adSettings['content'] }}');
                    @else
                        debugLog('Invalid ad configuration - using fallback');
                        handleAdError();
                    @endif
                @endif
            } catch (error) {
                // Handle any errors
                debugLog('ERROR: Exception when showing ad - ' + error.message);
                handleAdError();
            }
        });
        
        // Handle script-based ads (like show_9341414())
        function showScriptAd(scriptContent) {
            debugLog('Executing script-based ad');
            
            try {
                // Get required view time from settings
                const requiredViewTime = {{ $adViewTime ?? 30 }};
                let timeSpent = 0;
                let timerInterval = null;
                let adCompleted = false;
                
                // Create a unique container for this ad
                const adContainerId = 'ad-script-container-' + Date.now();
                const adContainer = document.createElement('div');
                adContainer.id = adContainerId;
                adContainer.style.position = 'fixed';
                adContainer.style.zIndex = '9999';
                adContainer.style.top = '0';
                adContainer.style.left = '0';
                adContainer.style.width = '100%';
                adContainer.style.height = '100%';
                adContainer.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
                adContainer.style.display = 'flex';
                adContainer.style.flexDirection = 'column';
                adContainer.style.alignItems = 'center';
                adContainer.style.justifyContent = 'center';
                document.body.appendChild(adContainer);
                
                // Create a header message
                const adHeaderMsg = document.createElement('div');
                adHeaderMsg.innerText = 'Watch Ad To Earn Coins';
                adHeaderMsg.style.position = 'absolute';
                adHeaderMsg.style.top = '10px';
                adHeaderMsg.style.left = '0';
                adHeaderMsg.style.width = '100%';
                adHeaderMsg.style.textAlign = 'center';
                adHeaderMsg.style.color = 'white';
                adHeaderMsg.style.fontWeight = 'bold';
                adHeaderMsg.style.fontSize = '18px';
                adHeaderMsg.style.padding = '5px';
                adHeaderMsg.style.textShadow = '0 1px 2px rgba(0,0,0,0.8)';
                adHeaderMsg.style.zIndex = '9999';
                adContainer.appendChild(adHeaderMsg);
                
                // Create timer display
                const timerDisplay = document.createElement('div');
                timerDisplay.id = 'ad-timer-display';
                timerDisplay.style.fontSize = '38px';
                timerDisplay.style.fontWeight = 'bold';
                timerDisplay.style.color = 'white';
                timerDisplay.style.margin = '20px 0';
                timerDisplay.innerText = `${requiredViewTime}`;
                adContainer.appendChild(timerDisplay);
                
                // Create status display
                const statusDisplay = document.createElement('div');
                statusDisplay.id = 'ad-status-display';
                statusDisplay.style.fontSize = '18px';
                statusDisplay.style.color = 'yellow';
                statusDisplay.style.margin = '10px 0';
                statusDisplay.innerText = 'Loading ad...';
                adContainer.appendChild(statusDisplay);
                
                // Add a loading spinner
                const loadingSpinner = document.createElement('div');
                loadingSpinner.classList.add('spinner');
                loadingSpinner.innerHTML = `<div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>`;
                adContainer.appendChild(loadingSpinner);
                
                // Create instructions
                const instructions = document.createElement('div');
                instructions.style.color = 'white';
                instructions.style.maxWidth = '90%';
                instructions.style.textAlign = 'center';
                instructions.style.margin = '20px 0';
                instructions.innerHTML = `
                    <p>Watch this ad for ${requiredViewTime} seconds to earn coins.</p>
                    <p>Keep this tab open and visible.</p>
                `;
                adContainer.appendChild(instructions);
                
                // Create a close button (initially disabled)
                const closeButton = document.createElement('button');
                closeButton.innerText = 'X';
                closeButton.style.position = 'absolute';
                closeButton.style.top = '10px';
                closeButton.style.right = '10px';
                closeButton.style.zIndex = '10000';
                closeButton.style.backgroundColor = '#888'; // Initially gray
                closeButton.style.color = 'white';
                closeButton.style.border = 'none';
                closeButton.style.borderRadius = '50%';
                closeButton.style.width = '40px';
                closeButton.style.height = '40px';
                closeButton.style.cursor = 'not-allowed';
                closeButton.style.fontSize = '16px';
                closeButton.style.fontWeight = 'bold';
                closeButton.disabled = true;
                adContainer.appendChild(closeButton);
                
                // Create a claim button (initially hidden)
                const claimButton = document.createElement('button');
                claimButton.innerText = 'Waiting...';
                claimButton.style.backgroundColor = '#888';
                claimButton.style.color = 'white';
                claimButton.style.padding = '12px 24px';
                claimButton.style.borderRadius = '24px';
                claimButton.style.border = 'none';
                claimButton.style.fontSize = '18px';
                claimButton.style.marginTop = '20px';
                claimButton.style.display = 'block';
                claimButton.style.width = '80%';
                claimButton.style.maxWidth = '280px';
                claimButton.disabled = true;
                adContainer.appendChild(claimButton);
                
                // Function to update the timer
                function updateTimer() {
                    timeSpent++;
                    const timeRemaining = Math.max(0, requiredViewTime - timeSpent);
                    timerDisplay.innerText = `${timeRemaining}`;
                    
                    // Check for visibility
                    if (document.hidden) {
                        pauseTimer('Tab no longer in focus. Timer paused.');
                        return;
                    }
                    
                    if (timeSpent >= requiredViewTime) {
                        clearInterval(timerInterval);
                        timerDisplay.innerText = 'Done!';
                        timerDisplay.style.color = '#4caf50';
                        statusDisplay.innerText = 'Congratulations! Your reward is ready.';
                        statusDisplay.style.color = '#4caf50';
                        
                        // Add success animation
                        timerDisplay.style.animation = 'pulse 0.5s 3';
                        
                        // Vibrate pattern on mobile if supported
                        if (navigator.vibrate) {
                            navigator.vibrate([50, 50, 100]);
                        }
                        
                        // Enable close button
                        closeButton.disabled = false;
                        closeButton.style.backgroundColor = '#e83b3b';
                        closeButton.style.cursor = 'pointer';
                        
                        // Enable claim button
                        claimButton.disabled = false;
                        claimButton.innerText = 'Claim Reward';
                        claimButton.style.backgroundColor = '#4caf50';
                        claimButton.style.cursor = 'pointer';
                        
                        // Mark ad as completed
                        adCompleted = true;
                        
                        // Add pulse animation to claim button
                        claimButton.style.animation = 'pulse 2s infinite';
                    }
                }
                
                // Function to pause timer
                function pauseTimer(message) {
                    if (timerInterval) {
                        clearInterval(timerInterval);
                        timerInterval = null;
                        statusDisplay.innerText = message;
                        statusDisplay.style.color = 'red';
                        debugLog('Timer paused: ' + message);
                    }
                }
                
                // Function to resume timer
                function resumeTimer() {
                    if (!timerInterval && timeSpent < requiredViewTime) {
                        timerInterval = setInterval(updateTimer, 1000);
                        statusDisplay.innerText = 'Ad is playing. Please wait...';
                        statusDisplay.style.color = 'yellow';
                        debugLog('Timer resumed');
                    }
                }
                
                // Style for animation
                const style = document.createElement('style');
                style.innerHTML = `
                    @keyframes pulse {
                        0% { transform: scale(1); }
                        50% { transform: scale(1.1); }
                        100% { transform: scale(1); }
                    }
                `;
                document.head.appendChild(style);
                
                // Set up visibility change detection
                document.addEventListener('visibilitychange', function() {
                    if (document.hidden) {
                        pauseTimer('Tab no longer in focus. Timer paused.');
                    } else {
                        resumeTimer();
                    }
                });
                
                // Add event listener to close button
                closeButton.addEventListener('click', function() {
                    if (adCompleted) {
                        // Clean up
                        document.removeEventListener('visibilitychange', function() {});
                        if (timerInterval) clearInterval(timerInterval);
                        
                        // Remove the ad container
                        if (adContainer.parentNode) {
                            document.body.removeChild(adContainer);
                        }
                        
                        // Mark ad as completed
                        adCompletedInput.value = '1';
                        
                        // Double check the value is set
                        if (document.getElementById('ad-completed')) {
                            document.getElementById('ad-completed').value = '1';
                        }
                        
                        submitForm();
                    }
                });
                
                // Handle claim button click
                claimButton.addEventListener('click', function() {
                    if (adCompleted) {
                        // Clean up
                        document.removeEventListener('visibilitychange', function() {});
                        if (timerInterval) clearInterval(timerInterval);
                        
                        // Remove the ad container
                        if (adContainer.parentNode) {
                            document.body.removeChild(adContainer);
                        }
                        
                        // Mark ad as completed
                        adCompletedInput.value = '1';
                        
                        // Double check the value is set
                        if (document.getElementById('ad-completed')) {
                            document.getElementById('ad-completed').value = '1';
                        }
                        
                        submitForm();
                    }
                });
                
                // Create a script element for executing the ad code
                const script = document.createElement('script');
                script.textContent = `
                    // Wrap the ad script in a try-catch to handle errors
                    try {
                        // Set window variables to track ad status
                        window.adWasShown = false;
                        window.adError = null;
                        
                        // Execute the script function
                        ${scriptContent}
                        
                        // Mark ad as successful
                        window.adWasShown = true;
                    } catch (e) {
                        window.adError = e.toString();
                        window.adWasShown = false;
                        console.error('Ad script error:', e);
                    }
                `;
                document.body.appendChild(script);
                
                // Check ad status every second
                const checkInterval = setInterval(() => {
                    if (window.adWasShown === true) {
                        debugLog('Ad was loaded successfully');
                        statusDisplay.innerText = 'Ad is playing. Please watch...';
                        loadingSpinner.style.display = 'none';
                        
                        // Start the timer now that the ad is showing
                        if (!timerInterval) {
                            timerInterval = setInterval(updateTimer, 1000);
                        }
                        
                        clearInterval(checkInterval);
                    } else if (window.adError) {
                        debugLog('ERROR: Ad failed to load - ' + window.adError);
                        pauseTimer('Failed to load ad. Please try again.');
                        handleAdError();
                        clearInterval(checkInterval);
                        
                        // Remove the ad container
                        setTimeout(() => {
                            if (adContainer.parentNode) {
                                document.body.removeChild(adContainer);
                            }
                        }, 2000);
                    }
                }, 1000);
                
                // Timeout for ad loading
                setTimeout(() => {
                    if (window.adWasShown !== true && !window.adError) {
                        window.adError = "Timeout while loading ad";
                        debugLog('ERROR: Ad timed out');
                        pauseTimer('Ad loading timeout. Please try again.');
                        handleAdError();
                        
                        // Remove the ad container
                        setTimeout(() => {
                            if (adContainer.parentNode) {
                                document.body.removeChild(adContainer);
                            }
                        }, 2000);
                    }
                }, 10000); // 10 seconds loading timeout
                
            } catch (error) {
                debugLog('ERROR: Failed to initialize script ad - ' + error.toString());
                handleAdError();
            }
        }
        
        // Handle URL-based ads
        function showUrlAd(adUrl) {
            debugLog('Loading URL-based ad: ' + adUrl);
            
            // Get required view time from settings
            const requiredViewTime = {{ $adViewTime ?? 30 }};
            let timeSpent = 0;
            let timerInterval = null;
            let adWindow = null;
            let adWindowClosed = false;
            
            // Create a container to show timer and instructions
            const adContainer = document.createElement('div');
            adContainer.id = 'ad-url-container-' + Date.now();
            adContainer.style.position = 'fixed';
            adContainer.style.zIndex = '9999';
            adContainer.style.top = '0';
            adContainer.style.left = '0';
            adContainer.style.width = '100%';
            adContainer.style.height = '100%';
            adContainer.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            adContainer.style.display = 'flex';
            adContainer.style.flexDirection = 'column';
            adContainer.style.alignItems = 'center';
            adContainer.style.justifyContent = 'center';
            document.body.appendChild(adContainer);
            
            // Create a header message
            const adHeaderMsg = document.createElement('div');
            adHeaderMsg.innerText = 'Watch Ad To Earn Coins';
            adHeaderMsg.style.position = 'absolute';
            adHeaderMsg.style.top = '10px';
            adHeaderMsg.style.left = '0';
            adHeaderMsg.style.width = '100%';
            adHeaderMsg.style.textAlign = 'center';
            adHeaderMsg.style.color = 'white';
            adHeaderMsg.style.fontWeight = 'bold';
            adHeaderMsg.style.fontSize = '18px';
            adHeaderMsg.style.padding = '5px';
            adHeaderMsg.style.textShadow = '0 1px 2px rgba(0,0,0,0.8)';
            adHeaderMsg.style.zIndex = '9999';
            adContainer.appendChild(adHeaderMsg);
            
            // Create timer display
            const timerDisplay = document.createElement('div');
            timerDisplay.id = 'ad-timer-display';
            timerDisplay.style.fontSize = '38px';
            timerDisplay.style.fontWeight = 'bold';
            timerDisplay.style.color = 'white';
            timerDisplay.style.margin = '20px 0';
            timerDisplay.innerText = `${requiredViewTime}`;
            adContainer.appendChild(timerDisplay);
            
            // Create status message display
            const statusDisplay = document.createElement('div');
            statusDisplay.id = 'ad-status-display';
            statusDisplay.style.fontSize = '18px';
            statusDisplay.style.color = 'yellow';
            statusDisplay.style.margin = '10px 0';
            statusDisplay.innerText = 'Ad loaded. Please keep the tab open.';
            adContainer.appendChild(statusDisplay);
            
            // Create instructions
            const instructions = document.createElement('div');
            instructions.style.color = 'white';
            instructions.style.maxWidth = '90%';
            instructions.style.textAlign = 'center';
            instructions.style.margin = '20px 0';
            instructions.innerHTML = `
                <p>A new tab has opened with the ad.</p>
                <p>Keep the ad tab open for ${requiredViewTime} seconds.</p>
                <p>If you close it, the timer will pause.</p>
            `;
            adContainer.appendChild(instructions);
            
            // Create a claim button (initially disabled)
            const claimButton = document.createElement('button');
            claimButton.innerText = 'Waiting for ad view...';
            claimButton.style.backgroundColor = '#888';
            claimButton.style.color = 'white';
            claimButton.style.padding = '12px 24px';
            claimButton.style.borderRadius = '24px';
            claimButton.style.border = 'none';
            claimButton.style.fontSize = '18px';
            claimButton.style.marginTop = '20px';
            claimButton.style.width = '80%';
            claimButton.style.maxWidth = '280px';
            claimButton.disabled = true;
            adContainer.appendChild(claimButton);
            
            // Create "Reopen Ad" button (initially hidden)
            const reopenButton = document.createElement('button');
            reopenButton.innerText = 'Reopen Ad';
            reopenButton.style.backgroundColor = '#e67e22';
            reopenButton.style.color = 'white';
            reopenButton.style.padding = '12px 24px';
            reopenButton.style.borderRadius = '24px';
            reopenButton.style.border = 'none';
            reopenButton.style.fontSize = '16px';
            reopenButton.style.marginTop = '10px';
            reopenButton.style.width = '80%';
            reopenButton.style.maxWidth = '280px';
            reopenButton.style.display = 'none';
            adContainer.appendChild(reopenButton);
            
            // Style for animation
            const style = document.createElement('style');
            style.innerHTML = `
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                    100% { transform: scale(1); }
                }
            `;
            document.head.appendChild(style);
            
            reopenButton.addEventListener('click', function() {
                adWindow = window.open(adUrl, '_blank');
                if (adWindow) {
                    statusDisplay.innerText = 'Ad reopened. Timer resumed.';
                    statusDisplay.style.color = 'lime';
                    reopenButton.style.display = 'none';
                    adWindowClosed = false;
                    
                    // Resume timer if it was paused
                    if (!timerInterval && timeSpent < requiredViewTime) {
                        timerInterval = setInterval(updateTimer, 1000);
                        debugLog('Ad timer resumed after reopening');
                    }
                    
                    // Check if the window gets closed
                    checkWindowStatus();
                }
            });
            
            // Function to check if ad window is still open
            function checkWindowStatus() {
                if (adWindow) {
                    const checkWindow = setInterval(() => {
                        try {
                            // This will throw an error if the window is closed
                            if (adWindow.closed) {
                                adWindowClosed = true;
                                statusDisplay.innerText = 'Ad tab closed. Timer paused. Please reopen.';
                                statusDisplay.style.color = 'red';
                                reopenButton.style.display = 'block';
                                
                                // Pause timer
                                if (timerInterval) {
                                    clearInterval(timerInterval);
                                    timerInterval = null;
                                    debugLog('Ad window closed - timer paused');
                                }
                                
                                clearInterval(checkWindow);
                            }
                        } catch (e) {
                            // Error means the window was closed or otherwise inaccessible
                            adWindowClosed = true;
                            statusDisplay.innerText = 'Ad tab closed. Timer paused. Please reopen.';
                            statusDisplay.style.color = 'red';
                            reopenButton.style.display = 'block';
                            
                            // Pause timer
                            if (timerInterval) {
                                clearInterval(timerInterval);
                                timerInterval = null;
                                debugLog('Ad window closed (exception) - timer paused');
                            }
                            
                            clearInterval(checkWindow);
                        }
                    }, 1000);
                }
            }
            
            // Function to update the timer
            function updateTimer() {
                if (adWindowClosed) {
                    // Don't increment time if the ad window is closed
                    return;
                }
                
                timeSpent++;
                timerDisplay.innerText = `${Math.max(0, requiredViewTime - timeSpent)}`;
                
                // Store current time in localStorage to track across tabs
                localStorage.setItem('adTimeSpent', timeSpent);
                
                if (timeSpent >= requiredViewTime) {
                    clearInterval(timerInterval);
                    timerDisplay.innerText = 'Done!';
                    timerDisplay.style.color = '#4caf50';
                    statusDisplay.innerText = 'Congratulations! Your reward is ready.';
                    statusDisplay.style.color = '#4caf50';
                    
                    // Add animation to timer
                    timerDisplay.style.animation = 'pulse 0.5s 3';
                    
                    // Vibrate on mobile if supported
                    if (navigator.vibrate) {
                        navigator.vibrate([50, 50, 100]);
                    }
                    
                    // Enable claim button
                    claimButton.disabled = false;
                    claimButton.innerText = 'Claim Reward';
                    claimButton.style.backgroundColor = '#4caf50';
                    claimButton.style.cursor = 'pointer';
                    claimButton.style.animation = 'pulse 2s infinite';
                    
                    // Mark ad as completed
                    adCompletedInput.value = '1';
                }
            }
            
            // Open ad in new tab
            adWindow = window.open(adUrl, '_blank');
            if (!adWindow) {
                // Popup was blocked
                debugLog('Ad popup was blocked by browser');
                statusDisplay.innerText = 'Ad popup was blocked. Please allow popups and try again.';
                statusDisplay.style.color = 'red';
                handleAdError();
                return;
            }
            
            // Start checking window status
            checkWindowStatus();
            
            // Start the timer
            timerInterval = setInterval(updateTimer, 1000);
            
            // Handle the claim button click
            claimButton.addEventListener('click', function() {
                if (timeSpent >= requiredViewTime) {
                    // Close the ad window if it's still open
                    if (adWindow && !adWindow.closed) {
                        adWindow.close();
                    }
                    
                    // Remove the container
                    document.body.removeChild(adContainer);
                    
                    // Make sure ad_completed is set to 1
                    adCompletedInput.value = '1';
                    
                    // Submit form to claim reward
                    submitForm();
                }
            });
            
            // Add a close button
            const closeButton = document.createElement('button');
            closeButton.innerText = 'X';
            closeButton.style.position = 'absolute';
            closeButton.style.top = '10px';
            closeButton.style.right = '10px';
            closeButton.style.zIndex = '10000';
            closeButton.style.backgroundColor = '#e83b3b';
            closeButton.style.color = 'white';
            closeButton.style.border = 'none';
            closeButton.style.borderRadius = '50%';
            closeButton.style.width = '40px';
            closeButton.style.height = '40px';
            closeButton.style.cursor = 'pointer';
            closeButton.style.fontSize = '16px';
            closeButton.style.fontWeight = 'bold';
            adContainer.appendChild(closeButton);
            
            // Handle close button click
            closeButton.addEventListener('click', function() {
                // Clear the interval
                clearInterval(timerInterval);
                
                // Clean up
                localStorage.removeItem('adTimeSpent');
                
                // Close the ad window if it's still open
                if (adWindow && !adWindow.closed) {
                    adWindow.close();
                }
                
                // Remove the container
                if (adContainer.parentNode) {
                    document.body.removeChild(adContainer);
                }
                
                // Check if we completed the ad view
                if (timeSpent >= requiredViewTime) {
                    submitForm();
                } else {
                    handleAdError();
                }
            });
        }
        
        // Legacy ad handling for backward compatibility
        function showLegacyAd(adUrl) {
            debugLog('Showing legacy ad with URL: ' + adUrl);
            showUrlAd(adUrl);
        }
        
        // Handle ad errors by showing the watch button again or using fallback
        function handleAdError() {
            debugLog('Ad failed to load or display - showing options');
            
            // Show watch button again
            watchButton.classList.remove('hidden');
            
            // Show error container and make it more visible
            if (adErrorContainer) {
                adErrorContainer.classList.remove('hidden');
                adErrorContainer.classList.add('animate-pulse');
                setTimeout(() => adErrorContainer.classList.remove('animate-pulse'), 2000);
            }
        }
        
        // Fallback button handler
        if (fallbackButton) {
            fallbackButton.addEventListener('click', function() {
                debugLog('Using fallback method');
                
                // Vibrate if supported
                if (navigator.vibrate) {
                    navigator.vibrate([30, 30, 100]);
                }
                
                fallbackUsedInput.value = '1';
                adCompletedInput.value = '1';
                submitForm();
            });
        }
        
        // Automatically submit the form to award coins
        function submitForm() {
            debugLog('Submitting form to award coins');
            // Make sure the ad_completed input is set to 1
            adCompletedInput.value = '1';
            
            // Show loading indicator
            const loadingOverlay = document.createElement('div');
            loadingOverlay.style.position = 'fixed';
            loadingOverlay.style.top = '0';
            loadingOverlay.style.left = '0';
            loadingOverlay.style.width = '100%';
            loadingOverlay.style.height = '100%';
            loadingOverlay.style.backgroundColor = 'rgba(0,0,0,0.7)';
            loadingOverlay.style.display = 'flex';
            loadingOverlay.style.flexDirection = 'column';
            loadingOverlay.style.alignItems = 'center';
            loadingOverlay.style.justifyContent = 'center';
            loadingOverlay.style.zIndex = '9999';
            
            const loadingText = document.createElement('div');
            loadingText.innerText = 'Claiming reward...';
            loadingText.style.color = 'white';
            loadingText.style.fontSize = '18px';
            loadingText.style.marginBottom = '20px';
            
            const spinner = document.createElement('div');
            spinner.innerHTML = `<div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>`;
            
            loadingOverlay.appendChild(loadingText);
            loadingOverlay.appendChild(spinner);
            document.body.appendChild(loadingOverlay);
            
            // Double check that the completion value is set
            if (document.getElementById('ad-completed')) {
                document.getElementById('ad-completed').value = '1';
            }
            
            // Submit the form after a slight delay
            setTimeout(() => {
                document.getElementById('ad-form').submit();
            }, 800);
        }
        
        debugLog('Setup complete - ad interface ready');
    });
</script>
@endif

<style>
/* Mobile optimization styles */
@media (max-width: 640px) {
    .container {
        padding-left: 8px;
        padding-right: 8px;
    }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
html.dark .bg-blue-50 {
    background-color: #1a1a1a;
}

</style>
@endsection
@include('partials.mobile-nav')