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