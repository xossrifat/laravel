@extends('layouts.admin')

@section('title', 'Chat with ' . $user->name)

@section('styles')
<style>
    .chat-container {
        height: calc(100vh - 270px);
        min-height: 400px;
    }
    
    .messages-container {
        height: calc(100% - 80px);
        overflow-y: auto;
        padding: 1rem;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    
    .message-input-container {
        margin-top: 1rem;
    }
    
    .admin-message .message-bubble {
        background-color: #0d6efd;
        color: #fff;
        border-radius: 1rem 1rem 0 1rem;
        padding: 0.75rem 1rem;
        max-width: 75%;
    }
    
    .user-message .message-bubble {
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 1rem 1rem 1rem 0;
        padding: 0.75rem 1rem;
        max-width: 75%;
    }
    
    .message-time {
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    
    .user-message .message-time {
        color: #6c757d;
    }
    
    .admin-message .message-time {
        color: #a7c5fd;
    }
    
    .typing-indicator {
        display: none;
        padding: 0.5rem 1rem;
        background: rgba(0,0,0,0.05);
        border-radius: 1rem;
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    
    .typing-indicator.active {
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h1>Chat with {{ $user->name }}</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.chat.index') }}">Live Chat</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </div>
        <div>
            <a href="{{ route('admin.chat.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to All Chats
            </a>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="user-avatar me-3 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-medium">{{ $user->name }}</div>
                        <div class="text-muted small">{{ $user->email }}</div>
                    </div>
                </div>
                <div>
                    <span class="user-status text-success">
                        <i class="fas fa-circle small"></i> Online
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="chat-container">
                <div id="messages-container" class="messages-container">
                    @if($messages->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-comments fa-3x text-muted"></i>
                            </div>
                            <p class="text-muted">No messages yet</p>
                        </div>
                    @else
                        <div id="messages-list">
                            @foreach($messages as $message)
                                @include('admin.chat.partials.message', ['message' => $message])
                            @endforeach
                        </div>
                    @endif
                    <div class="typing-indicator" id="typing-indicator">
                        <i class="fas fa-ellipsis-h me-2"></i> User is typing...
                    </div>
                </div>
                
                <div class="message-input-container p-3 border-top">
                    <form id="chat-form" action="{{ route('admin.chat.send', $user) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input 
                                type="text" 
                                class="form-control" 
                                id="message" 
                                name="message" 
                                placeholder="Type your message..." 
                                required
                                autocomplete="off"
                            >
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    const messagesList = document.getElementById('messages-list');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');
    
    let lastMessageId = {{ $messages->last() ? $messages->last()->id : 0 }};
    let isTyping = false;
    let typingTimer = null;
    
    // Scroll to bottom on load
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    // Check for new messages every 3 seconds
    setInterval(checkNewMessages, 3000);
    
    // Handle form submission
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            const formData = new FormData(chatForm);
            
            fetch('{{ route('admin.chat.send', $user) }}', {
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
                        if (!messagesList.innerHTML) {
                            // Clear the "no messages" display if it exists
                            messagesContainer.innerHTML = '<div id="messages-list"></div>';
                            messagesList = document.getElementById('messages-list');
                        }
                        
                        messagesList.insertAdjacentHTML('beforeend', data.html);
                        lastMessageId = data.message.id;
                        messageInput.value = '';
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    function checkNewMessages() {
        fetch('{{ route('admin.chat.check-new', $user) }}?last_id=' + lastMessageId, {
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
                    if (!messagesList.innerHTML) {
                        // Clear the "no messages" display if it exists
                        messagesContainer.innerHTML = '<div id="messages-list"></div>';
                        messagesList = document.getElementById('messages-list');
                    }
                    
                    const wasAtBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop <= messagesContainer.clientHeight + 100;
                    
                    messagesList.insertAdjacentHTML('beforeend', data.html);
                    lastMessageId = data.last_id;
                    
                    // If the user was at the bottom, scroll to the new message
                    if (wasAtBottom) {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                    
                    // Check if the message is from the user and simulate "read" status
                    if (data.messages && data.messages.length > 0) {
                        const hasUserMessages = data.messages.some(msg => !msg.is_admin);
                        if (hasUserMessages) {
                            // Play notification sound if available
                            const notificationSound = document.getElementById('notification-sound');
                            if (notificationSound) {
                                notificationSound.play().catch(e => console.log('Sound play prevented:', e));
                            }
                        }
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
@endsection 