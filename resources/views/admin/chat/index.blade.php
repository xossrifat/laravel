@extends('layouts.admin')

@section('title', 'Live Chat')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Live Chat Support</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Live Chat</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-comments me-1"></i>
            Chat Sessions
        </div>
        <div class="card-body">
            @if($users->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-comments fa-3x text-muted"></i>
                    </div>
                    <p class="text-muted">No chat sessions found</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Messages</th>
                                <th>Status</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $user->name }}</div>
                                                <div class="text-muted small">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $user->chat_messages_count }}</span>
                                        @if($user->unread_messages > 0)
                                            <span class="badge bg-danger ms-1">{{ $user->unread_messages }} new</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->unread_messages > 0)
                                            <span class="text-success"><i class="fas fa-circle small"></i> Active</span>
                                        @else
                                            <span class="text-muted"><i class="far fa-circle small"></i> Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->chatMessages->sortByDesc('created_at')->first()->created_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.chat.show', $user) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-comments me-1"></i> View Chat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Poll for new messages every 10 seconds
    setInterval(function() {
        fetch('{{ route('admin.chat.unread-count') }}')
            .then(response => response.json())
            .then(data => {
                // Update page title or show notification if needed
                if(data.count > 0) {
                    document.title = `(${data.count}) Live Chat - Admin`;
                } else {
                    document.title = 'Live Chat - Admin';
                }
            });
    }, 10000);
</script>
@endsection 