@extends('layouts.app')

@section('content')
<style>
    .wheel-container {
        position: relative;
        width: 280px;
        height: 280px;
        margin: 30px auto;
    }
    
    @media (min-width: 640px) {
        .wheel-container {
            width: 320px;
            height: 320px;
            margin: 50px auto;
        }
    }

    .wheel {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        position: relative;
        overflow: hidden;
        transition: transform 4s cubic-bezier(0.17, 0.67, 0.17, 1);
        transform: rotate(0deg); /* initial state */
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
        background-color: #f0f0f0;
        border: 12px solid #1a3a5c; /* Navy blue outer ring */
    }
    
    .wheel-segment {
        position: absolute;
        top: 0;
        right: 50%;
        bottom: 50%;
        left: 0;
        transform-origin: bottom right;
        clip-path: polygon(100% 100%, 0 0, 100% 0);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .segment-text {
        position: absolute;
        font-weight: bold;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        text-align: center;
        transform-origin: bottom right;
        font-size: 20px;
        line-height: 1.2;
        z-index: 6;
        margin-top: -20px;
    }

    .wheel-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 70px;
        height: 70px;
        background: white;
        border: 5px solid #1a3a5c;
        border-radius: 50%;
        z-index: 15;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        color: #1a3a5c;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        -webkit-tap-highlight-color: transparent;
    }

    @media (min-width: 640px) {
        .wheel-center {
            width: 80px;
            height: 80px;
            font-size: 20px;
        }
        
        .segment-text {
            font-size: 22px;
        }
    }

    .wheel-arrow {
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 30px;
        z-index: 16;
    }

    .arrow-down {
        position: absolute;
        top: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 0;
        border-left: 15px solid transparent;
        border-right: 15px solid transparent;
        border-top: 25px solid #e83b3b;
        filter: drop-shadow(0 2px 2px rgba(0,0,0,0.5));
    }
    
    .spin-stats {
        margin-top: 20px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .wheel-dots {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        pointer-events: none;
        z-index: 2;
    }

    .wheel-dot {
        position: absolute;
        width: 8px;
        height: 8px;
        background-color: white;
        border-radius: 50%;
    }

    /* Add text overlay that will appear on top of the wheel */
    .wheel-result-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 20;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .result-text {
        background-color: rgba(0, 0, 0, 0.8);
        color: #ffcc00;
        font-size: 20px;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 0 20px rgba(255, 204, 0, 0.5);
        border: 2px solid #ffcc00;
        text-shadow: 0 0 10px rgba(255, 204, 0, 0.7);
    }

    @media (min-width: 640px) {
        .result-text {
            font-size: 24px;
            padding: 15px 25px;
        }
    }

    .coins-won {
        font-size: 28px;
        margin-top: 10px;
        color: #ffffff;
    }
    
    @media (min-width: 640px) {
        .coins-won {
            font-size: 32px;
        }
    }
    
    .wheel-card {
        background: linear-gradient(to bottom right, #b8e1e9, #a1cad0);
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        margin: 10px;
    }
    
    @media (min-width: 768px) {
        .wheel-card {
            padding: 20px;
            margin: 0 auto;
        }
    }
    
    .wheel-card .card-header {
        background-color: transparent;
        color: #1a3a5c;
        font-weight: bold;
        border-bottom: none;
        text-align: center;
        font-size: 20px;
        padding: 10px;
    }
    
    @media (min-width: 640px) {
        .wheel-card .card-header {
            font-size: 22px;
            padding: 15px;
        }
    }
    
    .wheel-card .card-body {
        background-color: transparent;
        border-radius: 0 0 10px 10px;
        padding: 0px;
    }
    
    /* Spin button animation */
    @keyframes pulse-spin {
        0% {
            transform: translate(-50%, -50%) scale(1);
            box-shadow: 0 0 0 0 rgba(26, 58, 92, 0.7);
        }
        70% {
            transform: translate(-50%, -50%) scale(1.05);
            box-shadow: 0 0 0 10px rgba(26, 58, 92, 0);
        }
        100% {
            transform: translate(-50%, -50%) scale(1);
            box-shadow: 0 0 0 0 rgba(26, 58, 92, 0);
        }
    }
    
    .wheel-center:active {
        transform: translate(-50%, -50%) scale(0.95);
    }
    
    .pulse-animation {
        animation: pulse-spin 1.5s infinite;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card wheel-card">
                <div class="card-header">Spin & Win</div>
                
                <div class="card-body">
                    <div class="text-center">
                        <div class="wheel-container">
                            <div class="wheel-arrow">
                                <div class="arrow-down"></div>
                            </div>
                            <div id="wheel" class="wheel">
                                @foreach($rewards as $index => $reward)
                                <div class="wheel-segment" id="segment-{{ $index }}">
                                    <span class="segment-text">{{ $reward->coins }}</span>
                                </div>
                                @endforeach
                                <div class="wheel-dots" id="wheel-dots"></div>
                                <!-- Central spin button replaces the separate button -->
                                <div class="wheel-center pulse-animation" id="spinBtn" onclick="spinWheel()">SPIN</div>
                            </div>
                            <!-- Add result overlay -->
                            <div id="resultOverlay" class="wheel-result-overlay">
                                <div class="result-text">YOU WIN!</div>
                                <div id="coinsWon" class="coins-won"></div>
                            </div>
                        </div>
                        
                        <div class="spin-stats">
                            <p>Spins Left Today: <strong>{{ $spinsLeft }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script async="async" data-cfasync="false" src="//pl26656132.profitableratecpm.com/10015707975e74142502a6346d7b3950/invoke.js"></script>
    <div id="container-10015707975e74142502a6346d7b3950"></div>
    @include('layouts.social-ads')
    @include('layouts.banner')
</div>

<script>
    let isSpinning = false;
    let currentRotation = 0;
    const rewardColors = [
        '#e83b3b', // Red
        '#f6b801', // Yellow/Orange 
        '#2196f3', // Blue
        '#4caf50', // Green
    ];

    // Track if an ad is currently showing to prevent multiple ads
    let adIsShowing = false;
    
    document.addEventListener('DOMContentLoaded', function() {
        setupWheel();
        addWheelDots();
        
        // Add touch feedback
        const spinBtn = document.getElementById('spinBtn');
        spinBtn.addEventListener('touchstart', function() {
            this.classList.add('active');
        });
        spinBtn.addEventListener('touchend', function() {
            this.classList.remove('active');
        });
    });
    
    function addWheelDots() {
        const dotsContainer = document.getElementById('wheel-dots');
        const wheelSize = dotsContainer.offsetWidth;
        const wheelRadius = wheelSize / 2;
        const numDots = 12; // Only 12 dots as shown in the image
        
        for (let i = 0; i < numDots; i++) {
            const dot = document.createElement('div');
            dot.className = 'wheel-dot';
            
            // Position dots evenly around the wheel's circumference
            const angle = (i / numDots) * 2 * Math.PI;
            const x = wheelRadius + (wheelRadius - 10) * Math.cos(angle);
            const y = wheelRadius + (wheelRadius - 10) * Math.sin(angle);
            
            dot.style.left = `${x - 4}px`;
            dot.style.top = `${y - 4}px`;
            
            dotsContainer.appendChild(dot);
        }
    }
    
    function setupWheel() {
        const totalSegments = {{ $rewards->count() }};
        if (totalSegments === 0) return;
        
        const segmentAngle = 360 / totalSegments;
        const segments = document.querySelectorAll('.wheel-segment');
        
        segments.forEach((segment, index) => {
            const rotation = index * segmentAngle;
            const colorIndex = index % rewardColors.length;
            
            // Rotate segment to its position
            segment.style.transform = `rotate(${rotation}deg)`;
            segment.style.backgroundColor = rewardColors[colorIndex];
            
            // Position and rotate the text to be vertical along segment
            const text = segment.querySelector('.segment-text');
            const textRotation = segmentAngle / 2;
            const textDistance = 65;
            
            // Use rotateZ for vertical text alignment
            text.style.transform = `rotate(${textRotation}deg) translate(${textDistance}px, 0) rotateZ(90deg)`;
        });
        
        // When not spinning, add pulse animation
        if (!isSpinning) {
            document.getElementById('spinBtn').classList.add('pulse-animation');
        }
    }

    function spinWheel() {
        if (isSpinning || adIsShowing) return;
        
        const spinBtn = document.getElementById('spinBtn');
        spinBtn.style.pointerEvents = 'none';
        spinBtn.classList.remove('pulse-animation');
        isSpinning = true;

        // Add a "spinning..." text inside the button
        spinBtn.textContent = "...";

        // Mobile feedback - vibration if available
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }

        // Check if spin ads are enabled and show an ad before spinning
        @if(isset($spinAdsEnabled) && $spinAdsEnabled && !empty($spinAdUrls))
        // Show random ad from the configured spin ad URLs
        try {
            adIsShowing = true; // Mark that an ad is showing

            // Create a fresh ad container for each spin
            const adContainer = document.createElement('div');
            adContainer.id = 'spin-ad-container-' + Date.now(); // Use unique ID
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
            
            // Create a header message at the top of the ad container
            const adHeaderMsg = document.createElement('div');
            adHeaderMsg.innerText = 'Watch Ad To Earn Extra Coins';
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
            
            // Create a close button that appears after 30 seconds
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
            closeButton.style.width = '36px';
            closeButton.style.height = '36px';
            closeButton.style.display = 'none'; // Initially hidden
            closeButton.style.cursor = 'pointer';
            closeButton.style.fontSize = '16px';
            closeButton.style.fontWeight = 'bold';
            
            // Create an inner container for the ad
            const adInnerContainer = document.createElement('div');
            adInnerContainer.id = 'ad-content-container-' + Date.now(); // Use unique ID
            adInnerContainer.style.width = '100%';
            adInnerContainer.style.maxWidth = '728px'; // Larger max width to accommodate banner ads
            adInnerContainer.style.backgroundColor = 'white';
            adInnerContainer.style.padding = '20px';
            adInnerContainer.style.borderRadius = '10px';
            adInnerContainer.style.position = 'relative';
            adInnerContainer.style.overflow = 'visible'; // Changed from auto to visible
            adInnerContainer.style.maxHeight = '100vh'; // Increased height
            adInnerContainer.style.minHeight = '250px'; // Increased minimum height
            adInnerContainer.style.textAlign = 'center';
            
            // Add a loading message
            const loadingMsg = document.createElement('p');
            loadingMsg.innerText = 'Loading advertisement...';
            loadingMsg.style.color = '#666';
            adInnerContainer.appendChild(loadingMsg);
            
            adContainer.appendChild(adHeaderMsg);
            adContainer.appendChild(adInnerContainer);
            adContainer.appendChild(closeButton);
            document.body.appendChild(adContainer);
            
            // User interaction tracking
            let userInteracted = false;
            let adLoaded = false; // Flag to track if ad has loaded
            
            // Function to mark user as interacting with the ad
            const markInteraction = () => {
                // Only count interaction if ad has loaded
                if (adLoaded) {
                    userInteracted = true;
                    // Once user interacts with a loaded ad, show the close button
                    closeButton.style.display = 'block';
                }
            };
            
            // Temporary document-level event listener to catch interaction anywhere in the ad
            // We need this because some ad scripts create elements that might be outside our container
            const documentClickHandler = (e) => {
                // If the click happens inside the ad container AND ad is loaded, consider it an interaction
                if (adContainer.contains(e.target) && adLoaded) {
                    markInteraction();
                }
            };
            
            // Function to clean up when the ad is closed
            const cleanupAdListeners = () => {
                document.removeEventListener('click', documentClickHandler);
                document.removeEventListener('mousedown', documentClickHandler);
                document.removeEventListener('touchstart', documentClickHandler);
                adIsShowing = false; // Mark that ad is no longer showing
            };

            // Function to clean up ad and remove from DOM
            const removeAdContainer = () => {
                cleanupAdListeners();
                // Use try/catch in case the element has already been removed
                try {
                    if (document.body.contains(adContainer)) {
                        document.body.removeChild(adContainer);
                    }
                } catch(e) {
                    console.error("Error removing ad container:", e);
                }
                adIsShowing = false; // Always ensure this flag is reset 
            };
            
            // Get random ad URL from the configured list
            const spinAdUrls = @json($spinAdUrls);
            let randomAdContent = spinAdUrls[Math.floor(Math.random() * spinAdUrls.length)];
            
            // Add timestamp to prevent caching of ad content
            if (randomAdContent.includes('?')) {
                randomAdContent += '&_t=' + new Date().getTime() + '&_r=' + Math.random();
            } else {
                randomAdContent += '?_t=' + new Date().getTime() + '&_r=' + Math.random();
            }
            
            // Function to enable interaction after ad has loaded
            const enableAdInteraction = () => {
                adLoaded = true;
                // Now add interaction detection events to the ad container
                adInnerContainer.addEventListener('mousedown', markInteraction);
                adInnerContainer.addEventListener('touchstart', markInteraction);
                adInnerContainer.addEventListener('click', markInteraction);
                
                // Add the document level handlers now that ad is loaded
                document.addEventListener('click', documentClickHandler);
                document.addEventListener('mousedown', documentClickHandler);
                document.addEventListener('touchstart', documentClickHandler);
                
                // Remove the loading message if it still exists
                if (adInnerContainer.contains(loadingMsg)) {
                    adInnerContainer.removeChild(loadingMsg);
                }
            };
            
            // Check if the ad content is a URL or a complete HTML snippet
            if (randomAdContent.includes('<script') || randomAdContent.includes('<div')) {
                // It's a complete HTML snippet with script tags
                // Parse the HTML to extract script tags and other content
                const parser = new DOMParser();
                const doc = parser.parseFromString(randomAdContent, 'text/html');
                
                // First add any div containers
                const divs = doc.querySelectorAll('div');
                divs.forEach(div => {
                    const newDiv = document.createElement('div');
                    newDiv.id = div.id;
                    newDiv.className = div.className;
                    adInnerContainer.appendChild(newDiv);
                    // Do not add interaction listeners yet
                });
                
                // For specific profitableratecpm ads, use a direct approach
                if (randomAdContent.includes('profitableratecpm')) {
                    // Create a container specifically for this ad network
                    const profitContainer = document.getElementById('container-3a179ae28daf2b3a52802eb62ac86e86');
                    
                    // If container not found (because it wasn't created yet), create it directly
                    if (!profitContainer) {
                        const directHtmlContainer = document.createElement('div');
                        directHtmlContainer.id = 'container-3a179ae28daf2b3a52802eb62ac86e86';
                        adInnerContainer.appendChild(directHtmlContainer);
                    }
                    
                    // Create and inject the script directly
                    const directScript = document.createElement('script');
                    directScript.async = true;
                    directScript.setAttribute('data-cfasync', 'false');
                    directScript.src = '//pl26671223.profitableratecpm.com/3a179ae28daf2b3a52802eb62ac86e86/invoke.js';
                    document.body.appendChild(directScript);
                    
                    // Enable interaction after a delay to give the ad time to load
                    setTimeout(() => {
                        enableAdInteraction();
                    }, 2000);
                } else {
                    // For other ad types, use the existing approach
                    // Give the container a little time to render before loading scripts
                    setTimeout(() => {
                        // Then add scripts properly so they execute
                        const scripts = doc.querySelectorAll('script');
                        scripts.forEach(script => {
                            const newScript = document.createElement('script');
                            
                            // Copy all attributes
                            Array.from(script.attributes).forEach(attr => {
                                newScript.setAttribute(attr.name, attr.value);
                            });
                            
                            // Copy inline script content if any
                            if (script.innerHTML) {
                                newScript.innerHTML = script.innerHTML;
                            }
                            
                            // Append to document body to ensure it runs properly
                            document.body.appendChild(newScript);
                        });
                        
                        // Set up a mutation observer to detect if iframe elements are created
                        // This is needed because many ads will create iframes after the script runs
                        const observer = new MutationObserver((mutations) => {
                            mutations.forEach((mutation) => {
                                if (mutation.addedNodes) {
                                    mutation.addedNodes.forEach((node) => {
                                        // Check for iframes added to the ad container
                                        if (node.nodeName === 'IFRAME') {
                                            try {
                                                // Add interaction detection for the iframe after it loads
                                                node.addEventListener('load', () => {
                                                    try {
                                                        // Enable ad interaction once iframe is loaded
                                                        enableAdInteraction();
                                                        
                                                        // Try to add events to the iframe content if from the same origin
                                                        const iframeDoc = node.contentDocument || node.contentWindow.document;
                                                        iframeDoc.body.addEventListener('mousedown', markInteraction);
                                                        iframeDoc.body.addEventListener('touchstart', markInteraction);
                                                        iframeDoc.body.addEventListener('click', markInteraction);
                                                    } catch (e) {
                                                        // Cross-origin restriction, can't access iframe content
                                                        // Just handle interaction with the iframe itself
                                                    }
                                                });
                                                
                                                // Also add events to the iframe element itself
                                                node.addEventListener('mousedown', markInteraction);
                                                node.addEventListener('touchstart', markInteraction);
                                                node.addEventListener('click', markInteraction);
                                            } catch (e) {
                                                console.error('Error attaching iframe listeners:', e);
                                            }
                                        }
                                    });
                                }
                            });
                        });
                        
                        // Start observing for iframe creation
                        observer.observe(adInnerContainer, { 
                            childList: true, 
                            subtree: true 
                        });
                        
                        // Also observe the container div with ID "container-3a179ae28daf2b3a52802eb62ac86e86"
                        const containerDiv = document.getElementById('container-3a179ae28daf2b3a52802eb62ac86e86');
                        if (containerDiv) {
                            observer.observe(containerDiv, {
                                childList: true,
                                subtree: true
                            });
                            
                            // Check if we have content in the container div - this indicates ad has loaded
                            if (containerDiv.children.length > 0 || containerDiv.innerHTML.trim() !== '') {
                                enableAdInteraction();
                            }
                        }
                        
                        // Enable interaction after a reasonable timeout to account for ad loading
                        setTimeout(() => {
                            if (!adLoaded) {
                                enableAdInteraction();
                            }
                        }, 3000);
                    }, 100); // Short delay to ensure container is ready
                }
            } else {
                // It's just a URL, create a script tag
                const adScript = document.createElement('script');
                adScript.async = true;
                adScript.src = randomAdContent;
                adScript.onload = enableAdInteraction; // Enable interaction when script loads
                adInnerContainer.appendChild(adScript);
                
                // Fallback timeout in case onload doesn't fire
                setTimeout(() => {
                    if (!adLoaded) {
                        enableAdInteraction();
                    }
                }, 3000);
            }
            
            // Show close button after 30 seconds
            setTimeout(() => {
                closeButton.style.display = 'block';
            }, 30000);
            
            // Add event listener to close button
            closeButton.addEventListener('click', function() {
                removeAdContainer();
                // Continue with the spin after ad is closed
                performSpin();
            });
            
            // Auto-close ad and continue after 15 seconds ONLY if user hasn't interacted
 //       setTimeout(() => {
 //               if (document.body.contains(adContainer) && !userInteracted) {
 //                   cleanupAdListeners();
 //                   document.body.removeChild(adContainer);
 //                   // Continue with the spin after timeout
 //                   performSpin();
 //               }
 //           }, 15000); 
        } catch (error) {
            console.error('Error showing spin ad:', error);
            adIsShowing = false; // Reset flag if there was an error
            // Continue with spinning even if ad failed
            performSpin();
        }
        @else
        // No ads configured, proceed with spinning immediately
        performSpin();
        @endif
        
        function performSpin() {
            fetch('{{ route("spin.submit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
                    isSpinning = false;
                    adIsShowing = false; // Ensure ad flag is reset
                    spinBtn.style.pointerEvents = 'auto';
                    spinBtn.textContent = "SPIN";
                    spinBtn.classList.add('pulse-animation');
                    return;
                }

                const wheel = document.getElementById('wheel');
                
                // Reset the transition property temporarily
                wheel.style.transition = 'none';
                wheel.style.transform = `rotate(0deg)`;
                
                // Force a reflow to ensure the reset is applied
                void wheel.offsetWidth;
                
                // Restore the transition property
                wheel.style.transition = 'transform 4s cubic-bezier(0.17, 0.67, 0.17, 1)';
                
                // Calculate where to stop the wheel based on the segment index
                const segmentAngle = 360 / data.totalSegments;
                const stopAngle = 360 - (data.segmentIndex * segmentAngle) - (segmentAngle / 2);
                
                // Add some extra rotations for effect
                const spinDegree = 360 * 5 + stopAngle;
                currentRotation = spinDegree;
                
                // Apply the new rotation
                setTimeout(() => {
                    wheel.style.transform = `rotate(${currentRotation}deg)`;
                }, 50);

                setTimeout(() => {
                    // Update the overlay with the results
                    const overlay = document.getElementById('resultOverlay');
                    const coinsWon = document.getElementById('coinsWon');
                    coinsWon.textContent = `${data.reward} COINS`;
                    
                    // Show the overlay
                    overlay.style.opacity = '1';
                    
                    // Hide overlay after a delay and show alert
                    setTimeout(() => {
                        overlay.style.opacity = '0';
                        setTimeout(() => {
                            // Vibrate on mobile if supported
                            if (navigator.vibrate) {
                                navigator.vibrate([50, 50, 100]);
                            }
                            
                            // Create a nicer looking success notification
                            showWinNotification(data.reward, data.coins);
                            
                            isSpinning = false;
                            adIsShowing = false; // Reset ad flag after successful spin
                            spinBtn.style.pointerEvents = 'auto';
                            spinBtn.textContent = "SPIN";
                            
                            // Update spins left
                            const spinsLeft = {{ $spinsLeft }} - 1;
                            document.querySelector('.spin-stats strong').textContent = Math.max(0, spinsLeft);
                            
                            // Disable button if no spins left
                            if (spinsLeft <= 0) {
                                spinBtn.style.pointerEvents = 'none';
                                spinBtn.style.opacity = '0.5';
                            } else {
                                spinBtn.classList.add('pulse-animation');
                            }
                        }, 500);
                    }, 2000);
                }, 4500);
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Something went wrong!");
                isSpinning = false;
                adIsShowing = false; // Reset ad flag on error
                spinBtn.style.pointerEvents = 'auto';
                spinBtn.textContent = "SPIN";
                spinBtn.classList.add('pulse-animation');
            });
        }
    }
    
    function showWinNotification(reward, totalCoins) {
        // Create notification container
        const notifContainer = document.createElement('div');
        notifContainer.style.position = 'fixed';
        notifContainer.style.top = '50%';
        notifContainer.style.left = '50%';
        notifContainer.style.transform = 'translate(-50%, -50%)';
        notifContainer.style.backgroundColor = 'rgba(0, 0, 0, 0.85)';
        notifContainer.style.color = '#fff';
        notifContainer.style.padding = '20px';
        notifContainer.style.borderRadius = '10px';
        notifContainer.style.boxShadow = '0 0 20px rgba(255, 204, 0, 0.5)';
        notifContainer.style.textAlign = 'center';
        notifContainer.style.zIndex = '9999';
        notifContainer.style.minWidth = '250px';
        notifContainer.style.border = '2px solid #ffcc00';
        
        // Winning icon
        const iconDiv = document.createElement('div');
        iconDiv.style.fontSize = '40px';
        iconDiv.style.marginBottom = '10px';
        iconDiv.innerHTML = '🎉';
        
        // Win text
        const winText = document.createElement('div');
        winText.style.fontSize = '22px';
        winText.style.fontWeight = 'bold';
        winText.style.marginBottom = '5px';
        winText.style.color = '#ffcc00';
        winText.innerText = 'You won!';
        
        // Reward amount
        const rewardText = document.createElement('div');
        rewardText.style.fontSize = '28px';
        rewardText.style.fontWeight = 'bold';
        rewardText.style.color = '#ffffff';
        rewardText.style.margin = '10px 0';
        rewardText.innerText = `${reward} coins`;
        
        // Total coins
        const totalText = document.createElement('div');
        totalText.style.fontSize = '14px';
        totalText.style.color = '#aaaaaa';
        totalText.innerText = `Total balance: ${totalCoins} coins`;
        
        // OK button
        const okButton = document.createElement('button');
        okButton.innerText = 'AWESOME!';
        okButton.style.backgroundColor = '#4CAF50';
        okButton.style.color = 'white';
        okButton.style.border = 'none';
        okButton.style.padding = '10px 20px';
        okButton.style.margin = '15px 0 0 0';
        okButton.style.borderRadius = '5px';
        okButton.style.cursor = 'pointer';
        okButton.style.fontSize = '16px';
        okButton.style.fontWeight = 'bold';
        okButton.style.width = '100%';
        
        // Add elements to container
        notifContainer.appendChild(iconDiv);
        notifContainer.appendChild(winText);
        notifContainer.appendChild(rewardText);
        notifContainer.appendChild(totalText);
        notifContainer.appendChild(okButton);
        
        // Add to body
        document.body.appendChild(notifContainer);
        
        // Remove on button click and refresh the page
        okButton.addEventListener('click', function() {
            document.body.removeChild(notifContainer);
            // Refresh the page so ads will load properly on next spin
            window.location.reload();
        });
        
        // Auto-remove after 5 seconds and refresh the page
        setTimeout(() => {
            if (document.body.contains(notifContainer)) {
                document.body.removeChild(notifContainer);
                // Auto refresh the page after notification disappears
                window.location.reload();
            }
        }, 5000);
    }
</script>
@endsection
@include('partials.mobile-nav')