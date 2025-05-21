@extends('layouts.app')

@section('content')
<!-- Error Message Display -->
@if(session('error'))
<div class="max-w-4xl mx-auto mt-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
    <p class="font-bold">⚠️ {{ session('error') }}</p>
    @if(Auth::user()->is_banned)
    <p class="mt-2">
        Your account is currently suspended. Please contact support if you believe this is an error.
        <a href="{{ route('support.create') }}" class="text-red-800 underline">Contact Support</a>
    </p>
    @endif
</div>
@endif

@if(session('success'))
<div class="max-w-4xl mx-auto mt-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
    <p class="font-bold">✅ {{ session('success') }}</p>
</div>
@endif

<div class="max-w-4xl mx-auto mt-6 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <i class="fas fa-bell text-indigo-500 mr-3 text-xl"></i>
            <h2 class="text-2xl font-bold dark:text-white">Notifications</h2>
        </div>
        
        <div class="flex space-x-2">
            <div id="notification-count-badge" class="@if($unreadCount == 0) hidden @endif">
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 hover:bg-indigo-200 dark:hover:bg-indigo-800 px-3 py-1 rounded-full text-sm transition-colors">
                        Mark all as read
                    </button>
                </form>
                <div class="bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 px-3 py-1 rounded-full text-sm">
                    <span id="unread-count">{{ $unreadCount }}</span> unread
                </div>
            </div>
            <button id="refresh-notifications" class="bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 px-3 py-1 rounded-full text-sm transition-colors">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <div id="notifications-container" class="space-y-4 notifications-container" style="max-height: 70vh; overflow-y: auto;">
        @forelse($notifications as $notification)
            <div id="notification-{{ $notification->id }}" class="p-4 border-l-4 {{ $notification->getBackgroundClass() }} rounded-md dark:bg-opacity-10 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start">
                    <div class="mr-3 text-xl mt-1">
                        {!! $notification->getIconHtml() !!}
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h3 class="font-medium {{ $notification->getTextClass() }} dark:text-gray-100">
                                {{ $notification->title }}
                                @if(!$notification->is_read)
                                <span class="ml-2 bg-blue-500 text-white text-xs px-1.5 py-0.5 rounded-full new-badge">New</span>
                                @endif
                            </h3>
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <i class="far fa-clock mr-1"></i> <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $notification->message }}</p>
                        
                        @if(!$notification->is_read)
                            <div class="mt-3 text-right mark-read-container">
                                <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="inline mark-read-form">
                                    @csrf
                                    <button type="submit" class="text-xs bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                        <i class="far fa-check-circle mr-1"></i> Mark as read
                                    </button>
                                </form>
                            </div>
                        @endif
                        
                        @if($notification->sent_to_telegram)
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 telegram-status">
                                <i class="fab fa-telegram-plane text-blue-500"></i> Sent to Telegram
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div id="empty-notifications" class="text-center py-12 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <i class="far fa-bell-slash text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400">You don't have any notifications yet.</p>
            </div>
        @endforelse
    </div>

    <div id="pagination-container" class="mt-6 @if($notifications->count() == 0) hidden @endif">
        {{ $notifications->links() }}
    </div>
</div>

@if(Auth::user()->telegram_id)
<div class="max-w-4xl mx-auto mt-4 mb-12 p-4 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-200 rounded-md">
    <p class="flex items-center">
        <i class="fab fa-telegram-plane text-xl mr-2"></i>
        <span>Your notifications will also be sent to your Telegram account.</span>
    </p>
</div>
@else
<div class="max-w-4xl mx-auto mt-4 mb-12 p-4 bg-gray-50 dark:bg-gray-700 border-l-4 border-gray-300 text-gray-600 dark:text-gray-300 rounded-md">
    <p class="flex items-center">
        <i class="fab fa-telegram-plane text-xl mr-2"></i>
        <span>Connect your Telegram account to receive notifications in Telegram.</span>
    </p>
</div>
@endif

<!-- We're not using the bottom mobile nav here as it's now included in layouts/app.blade.php -->
@endsection 
@include('partials.mobile-nav')

@push('styles')
<style>
    /* Add some animations and custom styles for notifications */
    @keyframes highlightFade {
        0% { background-color: rgba(79, 70, 229, 0.2); }
        100% { background-color: transparent; }
    }
    
    .notification-highlight {
        animation: highlightFade 2s ease-out;
    }
    
    /* Custom scrollbar for notifications */
    .notifications-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .notifications-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .notifications-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    
    .notifications-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Animation for new notifications */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .new-notification {
        animation: fadeIn 0.5s ease-out;
    }
    
    /* Pulse animation for the badge */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .pulse {
        animation: pulse 1s infinite;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables
    const notificationsContainer = document.getElementById('notifications-container');
    const emptyNotificationsElement = document.getElementById('empty-notifications');
    const refreshButton = document.getElementById('refresh-notifications');
    const unreadCountElement = document.getElementById('unread-count');
    const notificationCountBadge = document.getElementById('notification-count-badge');
    const paginationContainer = document.getElementById('pagination-container');
    
    // Time between checks for new notifications (in milliseconds)
    const POLLING_INTERVAL = 15000; // 15 seconds
    
    // Track the notification IDs we've already seen
    let seenNotificationIds = getExistingNotificationIds();
    
    // Start polling for new notifications
    let pollingTimer = setInterval(checkForNewNotifications, POLLING_INTERVAL);
    
    // Also check immediately after page load
    setTimeout(checkForNewNotifications, 1000);
    
    // Add click event for the refresh button
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            // Show spinner
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
            
            // Force check for new notifications
            checkForNewNotifications().then(() => {
                // Restore button state
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-sync-alt"></i>';
                    this.disabled = false;
                }, 500);
            });
        });
    }
    
    // Setup mark-as-read handlers
    setupMarkAsReadHandlers();
    
    // Function to get existing notification IDs from the DOM
    function getExistingNotificationIds() {
        const ids = [];
        const notificationElements = document.querySelectorAll('[id^="notification-"]');
        
        notificationElements.forEach(element => {
            const id = element.id.replace('notification-', '');
            if (id) ids.push(parseInt(id, 10));
        });
        
        return ids;
    }
    
    // Function to check for new notifications
    async function checkForNewNotifications() {
        try {
            // Get the unread count first
            const countResponse = await fetch('{{ route("notifications.count") }}');
            const countData = await countResponse.json();
            
            // Update the unread count in the UI
            updateUnreadCount(countData.unread_count);
            
            // Get the first page of notifications
            const response = await fetch('{{ route("notifications.index") }}?ajax=1');
            
            if (!response.ok) {
                throw new Error('Failed to fetch notifications');
            }
            
            const html = await response.text();
            
            // Create a temporary div to parse the HTML
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            
            // Extract the notifications
            const newNotifications = tempDiv.querySelectorAll('[id^="notification-"]');
            
            // Check if we have any new notifications
            let hasNewNotifications = false;
            
            newNotifications.forEach(notification => {
                const id = parseInt(notification.id.replace('notification-', ''), 10);
                
                if (!seenNotificationIds.includes(id)) {
                    // This is a new notification
                    hasNewNotifications = true;
                    
                    // Add to seen list
                    seenNotificationIds.push(id);
                    
                    // Add the new notification to the top of the container with animation
                    notification.classList.add('new-notification');
                    
                    // If we had no notifications before, remove the empty state
                    if (emptyNotificationsElement && !emptyNotificationsElement.classList.contains('hidden')) {
                        emptyNotificationsElement.classList.add('hidden');
                        paginationContainer.classList.remove('hidden');
                    }
                    
                    // Insert at the top of the container
                    if (notificationsContainer.firstChild) {
                        notificationsContainer.insertBefore(notification, notificationsContainer.firstChild);
                    } else {
                        notificationsContainer.appendChild(notification);
                    }
                }
            });
            
            // If we found new notifications, play a sound and setup handlers
            if (hasNewNotifications) {
                // Play notification sound
                playNotificationSound();
                
                // Setup handlers for the new notifications
                setupMarkAsReadHandlers();
            }
            
            return true;
        } catch (error) {
            console.error('Error checking for notifications:', error);
            return false;
        }
    }
    
    // Function to update the unread count in the UI
    function updateUnreadCount(count) {
        if (unreadCountElement) {
            const oldCount = parseInt(unreadCountElement.textContent, 10);
            unreadCountElement.textContent = count;
            
            // If count increased, add pulse animation
            if (count > oldCount) {
                unreadCountElement.classList.add('pulse');
                setTimeout(() => {
                    unreadCountElement.classList.remove('pulse');
                }, 2000);
            }
            
            // Show/hide the badge container
            if (count > 0) {
                notificationCountBadge.classList.remove('hidden');
            } else {
                notificationCountBadge.classList.add('hidden');
            }
        }
    }
    
    // Function to play a notification sound
    function playNotificationSound() {
        try {
            // Create audio element
            const audio = new Audio('/sounds/notification.mp3');
            audio.volume = 0.5;
            audio.play();
        } catch (e) {
            console.log('Unable to play notification sound', e);
        }
    }
    
    // Function to setup mark-as-read handlers for forms
    function setupMarkAsReadHandlers() {
        document.querySelectorAll('.mark-read-form').forEach(form => {
            // Skip if we already setup this form
            if (form.dataset.handlerAttached === 'true') return;
            
            form.dataset.handlerAttached = 'true';
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: new URLSearchParams(new FormData(this))
                    });
                    
                    if (response.ok) {
                        // Find the notification element
                        const notificationElement = this.closest('[id^="notification-"]');
                        
                        // Remove the mark-read section
                        const markReadContainer = notificationElement.querySelector('.mark-read-container');
                        if (markReadContainer) markReadContainer.remove();
                        
                        // Remove the "New" badge
                        const newBadge = notificationElement.querySelector('.new-badge');
                        if (newBadge) newBadge.remove();
                        
                        // Change the left border color to gray
                        notificationElement.classList.remove(notificationElement.classList[2]); // Remove color class
                        notificationElement.classList.add('bg-gray-50', 'border-gray-300');
                        
                        // Fetch new count
                        checkForNewNotifications();
                    } else {
                        console.error('Failed to mark notification as read');
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            });
        });
    }
    
    // Cleanup when leaving the page
    window.addEventListener('beforeunload', function() {
        clearInterval(pollingTimer);
    });
});
</script>
@endpush