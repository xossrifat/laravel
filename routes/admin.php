<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SpinConfigController;
use App\Http\Controllers\Admin\WatchEarnController;
use App\Http\Controllers\Admin\AdsterraController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\DebugController;
use App\Http\Controllers\Admin\ShortlinkController as AdminShortlinkController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\EmailSettingController;
use App\Http\Controllers\Admin\TelegramConfigController;
use App\Http\Controllers\Admin\TelegramMessageController;
use App\Http\Controllers\Admin\WatchEarnControllerNew;
use App\Http\Controllers\Admin\TelegramUserController;
use App\Http\Controllers\Admin\LiveChatController as AdminLiveChatController;
use App\Http\Controllers\Admin\AboutSettingController;
use App\Http\Controllers\Admin\FeatureFlagController;
use App\Http\Controllers\UpdateReferralCodeController;
use App\Http\Controllers\FeatureController;

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
        
        // Debug route - for troubleshooting database issues
        Route::get('debug', [DebugController::class, 'debug'])->name('debug');

        // User Management
        Route::prefix('users')->as('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/{user}/ban', [UserController::class, 'toggleBan'])->name('toggle-ban');
            Route::get('/{user}/coins', [UserController::class, 'showCoins'])->name('coins');
            Route::post('/{user}/coins', [UserController::class, 'updateCoins'])->name('update-coins');
            Route::get('/{user}/spins', [UserController::class, 'spinHistory'])->name('spins');
            Route::get('/{user}/videos', [UserController::class, 'videoHistory'])->name('videos');
            Route::get('/{user}/referrals', [UserController::class, 'referralDetails'])->name('referrals');
        });

        // Spin Configuration
        Route::prefix('spin-config')->as('spin-config.')->group(function () {
            Route::get('/', [SpinConfigController::class, 'index'])->name('index');
            Route::post('/rewards', [SpinConfigController::class, 'updateRewards'])->name('rewards.update');
            Route::post('/probability', [SpinConfigController::class, 'updateProbability'])->name('probability.update');
            Route::post('/limits', [SpinConfigController::class, 'updateLimits'])->name('limits.update');
            Route::post('/ads', [SpinConfigController::class, 'updateAds'])->name('ads.update');
        });

        // Watch & Earn Control
        Route::prefix('watch-earn')->as('watch-earn.')->group(function () {
            Route::get('/', [WatchEarnController::class, 'index'])->name('index');
            Route::post('/config', [WatchEarnController::class, 'updateConfig'])->name('config.update');
            Route::post('/rewards', [WatchEarnController::class, 'updateRewards'])->name('rewards.update');
            Route::post('/limits', [WatchEarnController::class, 'updateLimits'])->name('limits.update');
            Route::post('/adsterra', [WatchEarnController::class, 'updateAdsterra'])->name('adsterra.update');
            
            // Video Ads Management
            Route::post('/video-ads', [WatchEarnControllerNew::class, 'storeVideoAd'])->name('video-ads.store');
            Route::put('/video-ads/{videoAdFixed}', [WatchEarnControllerNew::class, 'updateVideoAd'])->name('video-ads.update');
            Route::post('/video-ads/{videoAdFixed}/toggle', [WatchEarnControllerNew::class, 'toggleVideoAd'])->name('video-ads.toggle');
            Route::delete('/video-ads/{videoAdFixed}', [WatchEarnControllerNew::class, 'deleteVideoAd'])->name('video-ads.delete');
        });

        // Adsterra Integration
        Route::prefix('adsterra')->as('adsterra.')->group(function () {
            Route::get('/', [AdsterraController::class, 'index'])->name('index');
            Route::get('/reports', [AdsterraController::class, 'reports'])->name('reports');
            Route::get('/earnings', [AdsterraController::class, 'earnings'])->name('earnings');
            Route::get('/user-stats', [AdsterraController::class, 'userStats'])->name('user-stats');
        });

        // Transaction & Redemption
        Route::prefix('transactions')->as('transactions.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
            Route::get('/requests', [TransactionController::class, 'requests'])->name('requests');
            Route::post('/requests/{request}/approve', [TransactionController::class, 'approve'])->name('approve');
            Route::post('/requests/{request}/reject', [TransactionController::class, 'reject'])->name('reject');
            Route::get('/history', [TransactionController::class, 'history'])->name('history');
            Route::get('/config', [TransactionController::class, 'config'])->name('config');
            Route::post('/config', [TransactionController::class, 'updateConfig'])->name('config.update');
        });
        
        // Direct route for transaction config update in case the prefix route isn't working
        Route::post('/transactions-config-update', [TransactionController::class, 'updateConfig'])->name('transactions.config.direct-update');

        // Analytics
        Route::prefix('analytics')->as('analytics.')->group(function () {
            Route::get('/', [AnalyticsController::class, 'index'])->name('index');
            Route::get('/active-users', [AnalyticsController::class, 'activeUsers'])->name('active-users');
            Route::get('/spins', [AnalyticsController::class, 'spins'])->name('spins');
            Route::get('/videos', [AnalyticsController::class, 'videos'])->name('videos');
            Route::get('/revenue', [AnalyticsController::class, 'revenue'])->name('revenue');
            Route::get('/withdrawals', [AnalyticsController::class, 'withdrawals'])->name('withdrawals');
        });

        // Settings
        Route::prefix('settings')->as('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::post('/general', [SettingController::class, 'updateGeneral'])->name('general.update');
            Route::post('/appearance', [SettingController::class, 'updateAppearance'])->name('appearance.update');
            Route::post('/maintenance', [SettingController::class, 'updateMaintenance'])->name('maintenance.update');
            Route::post('/security', [SettingController::class, 'updateSecurity'])->name('security.update');
            
            // Add missing routes
            Route::post('/app-name', [SettingController::class, 'updateAppName'])->name('app-name.update');
            Route::post('/favicon', [SettingController::class, 'updateFavicon'])->name('favicon.update');
            Route::post('/coin-rate', [SettingController::class, 'updateCoinRate'])->name('coin-rate.update');
            Route::post('/redeem-conditions', [SettingController::class, 'updateRedeemConditions'])->name('redeem-conditions.update');
            Route::post('/limits', [SettingController::class, 'updateLimits'])->name('limits.update');
            Route::post('/maintenance/toggle', [SettingController::class, 'toggleMaintenance'])->name('maintenance.toggle');
            Route::post('/notifications/send', [SettingController::class, 'sendNotification'])->name('notifications.send');
            Route::post('/general-settings', [SettingController::class, 'updateGeneralSettings'])->name('general-settings.update');
            Route::post('/referral', [SettingController::class, 'updateReferralSettings'])->name('referral.update');
            
            // Feature flags
            Route::post('/features/toggle', [FeatureController::class, 'toggleFeature'])->name('features.toggle');
        });
        
        // Feature Flags Management
        Route::prefix('features')->as('features.')->group(function () {
            Route::get('/', [FeatureFlagController::class, 'index'])->name('index');
            Route::get('/create', [FeatureFlagController::class, 'create'])->name('create');
            Route::post('/store', [FeatureFlagController::class, 'store'])->name('store');
            Route::get('/{feature}/edit', [FeatureFlagController::class, 'edit'])->name('edit');
            Route::post('/{feature}/update', [FeatureFlagController::class, 'update'])->name('update');
            Route::post('/{feature}/toggle', [FeatureFlagController::class, 'toggle'])->name('toggle');
            Route::delete('/{feature}/delete', [FeatureFlagController::class, 'destroy'])->name('delete');
        });
        
        // About Page Settings
        Route::prefix('about-settings')->as('about-settings.')->group(function () {
            Route::get('/', [AboutSettingController::class, 'index'])->name('index');
            Route::post('/general', [AboutSettingController::class, 'updateGeneral'])->name('general.update');
            Route::post('/features', [AboutSettingController::class, 'updateFeatures'])->name('features.update');
            Route::post('/support', [AboutSettingController::class, 'updateSupport'])->name('support.update');
            Route::post('/legal', [AboutSettingController::class, 'updateLegal'])->name('legal.update');
        });
        
        // Add a route for updating referral codes
        Route::get('/update-referral-codes', [UpdateReferralCodeController::class, 'update'])->name('update-referral-codes');

        // Support Management
        Route::prefix('support')->as('support.')->group(function () {
            Route::get('/', [SupportController::class, 'index'])->name('index');
            Route::get('/{message}', [SupportController::class, 'show'])->name('show');
            Route::post('/{message}/reply', [SupportController::class, 'reply'])->name('reply');
            Route::post('/{message}/close', [SupportController::class, 'close'])->name('close');
            Route::post('/{message}/reopen', [SupportController::class, 'reopen'])->name('reopen');
        });

        // Email Templates
        Route::prefix('email-templates')->as('email_templates.')->group(function () {
            Route::get('/', [EmailTemplateController::class, 'index'])->name('index');
            Route::get('/{template}/edit', [EmailTemplateController::class, 'edit'])->name('edit');
            Route::post('/{template}/update', [EmailTemplateController::class, 'update'])->name('update');
            Route::post('/{template}/test', [EmailTemplateController::class, 'sendTest'])->name('test');
        });

        // Email Settings
        Route::prefix('email-settings')->as('email_settings.')->group(function () {
            Route::get('/', [EmailSettingController::class, 'index'])->name('index');
            Route::post('/', [EmailSettingController::class, 'update'])->name('update');
            Route::post('/test', [EmailSettingController::class, 'sendTest'])->name('test');
        });

        // Shortlinks Management
        Route::prefix('shortlinks')->as('shortlinks.')->group(function () {
            Route::get('/', [AdminShortlinkController::class, 'index'])->name('index');
            Route::get('/create', [AdminShortlinkController::class, 'create'])->name('create');
            Route::post('/create', [AdminShortlinkController::class, 'store'])->name('store');
            Route::get('/{shortlink}/edit', [AdminShortlinkController::class, 'edit'])->name('edit');
            Route::put('/{shortlink}/update', [AdminShortlinkController::class, 'update'])->name('update');
            Route::post('/{shortlink}/toggle', [AdminShortlinkController::class, 'toggle'])->name('toggle');
            Route::delete('/{shortlink}/delete', [AdminShortlinkController::class, 'destroy'])->name('delete');
            Route::get('/analytics', [AdminShortlinkController::class, 'analytics'])->name('analytics');
            Route::get('/config', [AdminShortlinkController::class, 'config'])->name('config');
            Route::post('/config', [AdminShortlinkController::class, 'updateConfig'])->name('config.update');
        });

        // Telegram Configuration
        Route::prefix('telegram')->as('telegram.')->group(function () {
            Route::get('/config', [TelegramConfigController::class, 'index'])->name('config');
            Route::post('/config', [TelegramConfigController::class, 'update'])->name('config.update');
            Route::post('/test', [TelegramConfigController::class, 'testConnection'])->name('config.test');
            
            // Telegram Messaging routes
            Route::get('/messages', [TelegramMessageController::class, 'index'])->name('messages');
            Route::post('/messages/send', [TelegramMessageController::class, 'sendMessage'])->name('send-message');
            Route::post('/messages/send-photo', [TelegramMessageController::class, 'sendPhoto'])->name('send-photo');
            Route::post('/messages/test', [TelegramMessageController::class, 'sendTestMessage'])->name('send-test');
            
            // Telegram Users Dashboard & Management routes
            Route::get('/dashboard', [TelegramUserController::class, 'dashboard'])->name('dashboard');
            Route::get('/users', [TelegramUserController::class, 'index'])->name('users');
            Route::get('/users/{user}', [TelegramUserController::class, 'show'])->name('user');
            Route::post('/users/{user}/send-message', [TelegramUserController::class, 'sendMessage'])->name('user.send-message');
            Route::post('/users/{user}/update-info', [TelegramUserController::class, 'updateTelegramInfo'])->name('user.update-info');
            Route::post('/users/bulk-action', [TelegramUserController::class, 'bulkAction'])->name('users.bulk-action');
            Route::post('/users/{user}/toggle-ban', [TelegramUserController::class, 'toggleBan'])->name('user.toggle-ban');
        });

        // Admin Live Chat Routes
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [AdminLiveChatController::class, 'index'])->name('index');
            Route::get('/users/{user}', [AdminLiveChatController::class, 'show'])->name('show');
            Route::post('/users/{user}/send', [AdminLiveChatController::class, 'sendMessage'])->name('send');
            Route::get('/users/{user}/check-new', [AdminLiveChatController::class, 'checkNew'])->name('check-new');
            Route::get('/unread-count', [AdminLiveChatController::class, 'getUnreadCount'])->name('unread-count');
        });
    });
}); 