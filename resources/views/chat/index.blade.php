@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-6 p-0 bg-white rounded-2xl shadow-lg overflow-hidden">
    <!-- Chat header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6">
        <div class="flex items-center">
            <div class="mr-4">
                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Live Support Chat</h2>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                    <span class="text-sm text-indigo-100">Our team is online</span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-6 my-2 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Chat container -->
    <div class="chat-container p-6">
        <div id="messages-container" class="bg-gray-50 rounded-xl h-96 overflow-y-auto flex flex-col px-4 py-3 border border-gray-200">
            @if($messages->isEmpty())
                <div class="flex items-center justify-center h-full">
                    <div class="text-center p-6 bg-white rounded-xl shadow-sm max-w-md">
                        <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Start a Conversation</h3>
                        <p class="text-gray-500 mb-3">Our support team is here to help you with any questions or issues you might have.</p>
                        <div class="text-sm text-indigo-500">Type a message below to get started</div>
                    </div>
                </div>
            @else
                <div id="more-messages-indicator" class="hidden text-center py-2 my-2">
                    <button id="load-more-btn" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-full text-sm hover:bg-indigo-100 transition-colors flex items-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        Load previous messages
                    </button>
                </div>
                <div id="messages-list" class="space-y-3">
                    @foreach($messages as $message)
                        @include('chat.partials.message', ['message' => $message])
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Chat typing indicator -->
        <div id="typing-indicator" class="hidden px-4 py-2 text-sm text-gray-500 flex items-center mt-2">
            <div class="flex space-x-1 mr-2">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
            </div>
            Support team is typing...
        </div>

        <!-- Message input -->
        <form id="chat-form" action="{{ route('chat.store') }}" method="POST" class="mt-4">
            @csrf
            <div class="relative flex items-center">
                <input 
                    type="text" 
                    name="message" 
                    id="message" 
                    placeholder="Type your message..." 
                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent pr-16"
                    required
                    autocomplete="off"
                />
                <div class="absolute right-2">
                    <button 
                        type="submit" 
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-3 rounded-full hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 shadow-md"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>

        <!-- Chat footer -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                Our support team typically responds within a few minutes during business hours (9am-6pm)
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    const messagesList = document.getElementById('messages-list');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');
    const loadMoreBtn = document.getElementById('load-more-btn');
    const moreMessagesIndicator = document.getElementById('more-messages-indicator');
    const typingIndicator = document.getElementById('typing-indicator');
    
    let lastMessageId = {{ $messages->last() ? $messages->last()->id : 0 }};
    let firstMessageId = {{ $messages->first() ? $messages->first()->id : 0 }};
    let isLoading = false;
    
    // Scroll to bottom on load
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    // Check for new messages every 5 seconds
    setInterval(checkNewMessages, 5000);
    
    // Handle form submission
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Disable the input and button during submission
            messageInput.disabled = true;
            
            const formData = new FormData(chatForm);
            
            fetch('{{ route('chat.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (messagesList) {
                        // If this is the first message, clear the empty state
                        if (!messagesList.innerHTML) {
                            messagesContainer.innerHTML = '<div id="messages-list" class="space-y-3"></div>';
                            messagesList = document.getElementById('messages-list');
                        }
                        
                        messagesList.insertAdjacentHTML('beforeend', data.html);
                        lastMessageId = data.message.id;
                        messageInput.value = '';
                        messageInput.focus();
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        
                        // Show typing indicator for 2-4 seconds
                        setTimeout(() => {
                            typingIndicator.classList.remove('hidden');
                            setTimeout(() => {
                                typingIndicator.classList.add('hidden');
                            }, Math.random() * 2000 + 2000);
                        }, 1000);
                    }
                }
                messageInput.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                messageInput.disabled = false;
            });
        });
    }
    
    // Load more messages when clicking the button
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            loadMoreMessages();
        });
    }
    
    function loadMoreMessages() {
        if (isLoading || firstMessageId === 0) return;
        
        isLoading = true;
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerHTML = '<span class="inline-block animate-spin mr-1">↻</span> Loading...';
        
        fetch(`{{ route('chat.loadMore') }}?before_id=${firstMessageId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.html) {
                if (messagesList) {
                    const scrollHeight = messagesContainer.scrollHeight;
                    const scrollPosition = messagesContainer.scrollTop;
                    
                    messagesList.insertAdjacentHTML('afterbegin', data.html);
                    
                    if (data.messages.length > 0) {
                        firstMessageId = data.messages[0].id;
                    }
                    
                    // Maintain scroll position
                    messagesContainer.scrollTop = messagesContainer.scrollHeight - scrollHeight + scrollPosition;
                    
                    // Show/hide load more button
                    moreMessagesIndicator.classList.toggle('hidden', !data.has_more);
                }
            } else {
                moreMessagesIndicator.classList.add('hidden');
            }
            isLoading = false;
            loadMoreBtn.disabled = false;
            loadMoreBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg> Load previous messages';
        })
        .catch(error => {
            console.error('Error:', error);
            isLoading = false;
            loadMoreBtn.disabled = false;
            loadMoreBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg> Load previous messages';
        });
    }
    
    function checkNewMessages() {
        fetch(`{{ route('chat.checkNew') }}?last_id=${lastMessageId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.html) {
                if (messagesList) {
                    // If this is the first message, clear the empty state
                    if (!messagesList.innerHTML) {
                        messagesContainer.innerHTML = '<div id="messages-list" class="space-y-3"></div>';
                        messagesList = document.getElementById('messages-list');
                    }
                    
                    const wasAtBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop <= messagesContainer.clientHeight + 100;
                    
                    messagesList.insertAdjacentHTML('beforeend', data.html);
                    lastMessageId = data.last_id;
                    
                    // If the user was at the bottom, scroll to the new message
                    if (wasAtBottom) {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    } else {
                        // Show new message notification
                        const notification = document.createElement('div');
                        notification.classList.add('new-message-notification', 'fixed', 'bottom-24', 'left-1/2', 'transform', '-translate-x-1/2', 'bg-indigo-600', 'text-white', 'px-4', 'py-2', 'rounded-full', 'shadow-lg', 'cursor-pointer', 'z-50');
                        notification.textContent = 'New message ↓';
                        notification.addEventListener('click', () => {
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                            notification.remove();
                        });
                        document.body.appendChild(notification);
                        
                        // Remove after 5 seconds
                        setTimeout(() => {
                            notification.remove();
                        }, 5000);
                    }
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    // Focus on the message input
    if (messageInput) {
        messageInput.focus();
    }
});
</script>

<style>
/* Chat animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.message-item {
    animation: fadeIn 0.3s ease-out forwards;
}

/* New message notification animation */
.new-message-notification {
    animation: bounceIn 0.5s ease-out forwards;
}

@keyframes bounceIn {
    0% { opacity: 0; transform: translate(-50%, 20px); }
    50% { opacity: 1; transform: translate(-50%, -5px); }
    70% { transform: translate(-50%, 2px); }
    100% { transform: translate(-50%, 0); }
}

/* Custom scrollbar for messages container */
#messages-container::-webkit-scrollbar {
    width: 6px;
}

#messages-container::-webkit-scrollbar-track {
    background: transparent;
}

#messages-container::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 20px;
}

/* Dark mode adjustments */
html.dark #messages-container {
    background-color: #2a2a2a;
    border-color: #3a3a3a;
}

html.dark #message {
    background-color: #2a2a2a;
    border-color: #3a3a3a;
    color: #e5e5e5;
}
</style>
@endpush 