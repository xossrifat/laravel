<!-- This is just a partial that can be included in your existing login page -->
<div class="telegram-login-container my-6">
    <!-- Telegram login button - styled with Tailwind -->
    <button id="telegram-login-btn" class="hidden w-full flex items-center justify-center px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition duration-150 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"/>
        </svg>
        <span>Continue with Telegram</span>
    </button>
    
    <!-- Improved status messages with Tailwind classes -->
    <div id="telegram-auth-loading" class="hidden bg-blue-100 text-blue-700 p-4 rounded-lg my-4 flex items-center justify-center">
        <svg class="w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Authenticating with Telegram...</span>
    </div>
    
    <div id="telegram-auth-error" class="hidden bg-red-100 text-red-700 p-4 rounded-lg my-4"></div>
    <div id="telegram-auth-success" class="hidden bg-green-100 text-green-700 p-4 rounded-lg my-4"></div>
    
    <!-- Telegram Mini App detection notice -->
    <div id="telegram-mini-app-notice" class="hidden text-center mt-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-700">
            <i class="fas fa-info-circle mr-1"></i>
            If you're viewing this from the Telegram app, you will be logged in automatically.
        </div>
    </div>
</div>

<!-- Include necessary scripts -->
<script src="https://telegram.org/js/telegram-web-app.js"></script>
<script src="{{ asset('js/telegram-auth.js') }}"></script>

<!-- Auto-detect if in Telegram Mini App environment and show appropriate UI -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isTelegramWebApp = window.Telegram && 
                               window.Telegram.WebApp && 
                               window.Telegram.WebApp.initData && 
                               window.Telegram.WebApp.initData.length > 0;
                               
        const telegramBtn = document.getElementById('telegram-login-btn');
        const miniAppNotice = document.getElementById('telegram-mini-app-notice');
        
        if (isTelegramWebApp) {
            // In Telegram Mini App - auto-login will happen from the main script
            if (miniAppNotice) miniAppNotice.classList.remove('hidden');
        } else {
            // Regular web browser - show login button
            if (telegramBtn) {
                telegramBtn.classList.remove('hidden');
                telegramBtn.addEventListener('click', function() {
                    if (typeof handleTelegramLogin === 'function') {
                        handleTelegramLogin();
                    } else {
                        console.error('handleTelegramLogin function not found');
                        alert('Error: Telegram login functionality not loaded correctly.');
                    }
                });
            }
        }
    });
</script> 