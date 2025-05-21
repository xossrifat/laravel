<div class="message {{ $message->is_admin ? 'admin-message' : 'user-message' }} mb-3">
    <div class="d-flex {{ $message->is_admin ? 'justify-content-end' : 'justify-content-start' }}">
        <div class="message-bubble">
            <div class="message-content">{{ $message->message }}</div>
            <div class="message-time text-end">
                {{ $message->created_at->format('h:i A') }}
                @if($message->read_at && $message->is_admin)
                    <i class="fas fa-check-double ms-1" title="Read"></i>
                @endif
            </div>
        </div>
    </div>
</div> 