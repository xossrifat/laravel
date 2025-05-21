/**
 * Telegram Mini App Authentication Handler
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Telegram auth script loaded');
    
    // More robust detection of Telegram WebApp environment
    const isTelegramWebApp = window.Telegram && 
                           window.Telegram.WebApp && 
                           window.Telegram.WebApp.initData && 
                           window.Telegram.WebApp.initData.length > 0;
    
    // Extract referral code from startapp parameter (for Telegram Mini App)
    let referralCode = null;
    
    // Parse startapp parameter from WebApp
    if (window.Telegram && window.Telegram.WebApp) {
        const startParams = window.Telegram.WebApp.initDataUnsafe.start_param;
        console.log('Telegram start params:', startParams);
        
        if (startParams && startParams.startsWith('referral_')) {
            referralCode = startParams.substring(9);
            console.log('Found referral code in startapp:', referralCode);
        }
    }
    
    // Alternative method: Try to get from URL
    if (!referralCode) {
        const url = window.location.href;
        if (url.includes('startapp=referral_')) {
            const match = url.match(/startapp=referral_([A-Z0-9]+)/i);
            if (match && match[1]) {
                referralCode = match[1];
                console.log('Found referral code in URL:', referralCode);
            }
        }
    }
    
    // Store the referral code for login
    if (referralCode) {
        localStorage.setItem('telegram_referral_code', referralCode);
    }
    
    if (isTelegramWebApp) {
        console.log('Telegram WebApp detected with valid initData');
        
        // Get the button if it exists
        const telegramLoginBtn = document.getElementById('telegram-login-btn');
        if (telegramLoginBtn) {
            telegramLoginBtn.addEventListener('click', handleTelegramLogin);
            telegramLoginBtn.style.display = 'block';
        }
        
        // Hide regular login buttons and show Telegram welcome
        const regularLoginButtons = document.getElementById('regular-login-buttons');
        const telegramWelcome = document.getElementById('telegram-welcome');
        
        if (regularLoginButtons) regularLoginButtons.style.display = 'none';
        if (telegramWelcome) {
            // Get user data if available
            try {
                const userData = window.Telegram.WebApp.initDataUnsafe.user;
                if (userData) {
                    const userName = userData.first_name || 'Telegram User';
                    const fullName = userData.last_name ? `${userName} ${userData.last_name}` : userName;
                    telegramWelcome.querySelector('h2').textContent = `Welcome, ${fullName}!`;
                }
            } catch (e) {
                console.warn('Could not get Telegram user data:', e);
            }
            
            telegramWelcome.classList.remove('hidden');
            telegramWelcome.style.display = 'block';
        }
        
        // Always auto-login when in Telegram Mini App
        console.log('Telegram Mini App detected, automatically logging in...');
        handleTelegramLogin();
    } else {
        console.log('Not in Telegram WebApp environment or missing initData - showing regular login');
        
        // Hide Telegram-specific elements
        const telegramLoginBtn = document.getElementById('telegram-login-btn');
        if (telegramLoginBtn) {
            telegramLoginBtn.style.display = 'none';
        }
        
        const telegramWelcome = document.getElementById('telegram-welcome');
        if (telegramWelcome) {
            telegramWelcome.style.display = 'none';
        }
        
        // Explicitly show regular login buttons for web users
        const regularLoginButtons = document.getElementById('regular-login-buttons');
        if (regularLoginButtons) {
            regularLoginButtons.style.display = 'block';
            regularLoginButtons.classList.remove('hidden');
        }
    }
});

/**
 * Handle Telegram Login process
 */
function handleTelegramLogin() {
    try {
        showTelegramLoading('Authenticating with Telegram...');
        
        // Get Telegram init data
        const initData = window.Telegram.WebApp.initData;
        const userData = window.Telegram.WebApp.initDataUnsafe.user;
        
        console.log('Telegram user data:', userData);
        
        if (!initData) {
            console.error('No Telegram init data available');
            showTelegramError('Could not get Telegram user data');
            return;
        }
        
        // Get CSRF token for Laravel
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found. Make sure to add <meta name="csrf-token" content="{{ csrf_token() }}"> in your HTML head.');
            showTelegramError('Security configuration error. Please contact support.');
            return;
        }
        
        // Get stored referral code if available
        const referralCode = localStorage.getItem('telegram_referral_code');
        
        // Send to backend
        fetch('/telegram-login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                initData: initData,
                referral_code: referralCode
            })
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 419) {
                    throw new Error('CSRF token mismatch. Please refresh the page.');
                }
                return response.json().then(data => {
                    throw new Error(data.message || `Server error: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log('Telegram login successful', data);
                localStorage.setItem('telegram_auto_login', '1');
                
                // Clear referral code after successful login
                localStorage.removeItem('telegram_referral_code');
                
                // Show success message if needed
                if (data.message) {
                    showTelegramSuccess(data.message);
                }
                
                // Redirect to dashboard or specified route
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 500);
                }
            } else {
                console.error('Telegram login failed:', data.message);
                showTelegramError(data.message || 'Login failed');
            }
        })
        .catch(error => {
            console.error('Telegram login error:', error);
            showTelegramError(error.message || 'Could not connect to server');
        });
    } catch (error) {
        console.error('Telegram login exception:', error);
        showTelegramError('An error occurred during login');
    }
}

/**
 * Show loading state to user
 */
function showTelegramLoading(message) {
    // Get elements
    const errorElement = document.getElementById('telegram-auth-error');
    const successElement = document.getElementById('telegram-auth-success');
    const loadingElement = document.getElementById('telegram-auth-loading');
    
    // Hide error and success
    if (errorElement) {
        errorElement.classList.add('hidden');
        errorElement.style.display = 'none';
    }
    
    if (successElement) {
        successElement.classList.add('hidden');
        successElement.style.display = 'none';
    }
    
    // Show loading
    if (loadingElement) {
        loadingElement.classList.remove('hidden');
        loadingElement.style.display = 'block';
    } else {
        // Create loading element if it doesn't exist
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'telegram-auth-loading';
        loadingDiv.className = 'bg-blue-100 text-blue-700 p-4 rounded-lg mb-4 text-center';
        loadingDiv.innerHTML = `
            <svg class="inline w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            ${message || 'Logging in via Telegram...'}
        `;
        
        // Add to page in a good location
        const loginContainer = document.querySelector('.flex.flex-col.space-y-4');
        if (loginContainer) {
            loginContainer.prepend(loadingDiv);
        } else {
            document.body.appendChild(loadingDiv);
        }
    }
}

/**
 * Show error message to user
 */
function showTelegramError(message) {
    // Get elements
    const loadingElement = document.getElementById('telegram-auth-loading');
    if (loadingElement) {
        loadingElement.classList.add('hidden');
        loadingElement.style.display = 'none';
    }
    
    const errorElement = document.getElementById('telegram-auth-error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
        errorElement.style.display = 'block';
    } else {
        alert('Error: ' + message);
    }
}

/**
 * Show success message to user
 */
function showTelegramSuccess(message) {
    // Get elements
    const loadingElement = document.getElementById('telegram-auth-loading');
    if (loadingElement) {
        loadingElement.classList.add('hidden');
        loadingElement.style.display = 'none';
    }
    
    const errorElement = document.getElementById('telegram-auth-error');
    if (errorElement) {
        errorElement.classList.add('hidden');
        errorElement.style.display = 'none';
    }
    
    const successElement = document.getElementById('telegram-auth-success');
    if (successElement) {
        successElement.textContent = message;
        successElement.classList.remove('hidden');
        successElement.style.display = 'block';
    }
} 