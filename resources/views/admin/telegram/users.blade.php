@extends('layouts.admin')

@section('title', 'Telegram Users')

@push('styles')
<style>
    .user-avatar {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        color: white;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .user-row.banned {
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .status-badge {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 6px;
    }
    
    .status-active {
        background-color: #198754;
    }
    
    .status-inactive {
        background-color: #6c757d;
    }
    
    .status-banned {
        background-color: #dc3545;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Telegram Users</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.telegram.dashboard') }}">Telegram Dashboard</a></li>
            <li class="breadcrumb-item active">Users</li>
        </ol>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-filter me-1"></i>
                Filters
            </div>
            <div class="card-body">
                <form action="{{ route('admin.telegram.users') }}" method="GET" class="row g-3 align-items-end">
                    <!-- Search -->
                    <div class="col-md-5">
                        <label for="search" class="form-label">Search</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" 
                                placeholder="Name, username, Telegram ID..." value="{{ $filters['search'] ?? '' }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All</option>
                            <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="banned" {{ ($filters['status'] ?? '') == 'banned' ? 'selected' : '' }}>Banned</option>
                        </select>
                    </div>
                    
                    <!-- Sort -->
                    <div class="col-md-3">
                        <label for="sort" class="form-label">Sort by</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="created_at" {{ $sort == 'created_at' ? 'selected' : '' }}>Registration Date</option>
                            <option value="last_login_at" {{ $sort == 'last_login_at' ? 'selected' : '' }}>Last Login</option>
                            <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="coins" {{ $sort == 'coins' ? 'selected' : '' }}>Coins</option>
                        </select>
                    </div>
                    
                    <!-- Direction -->
                    <div class="col-md-1">
                        <label for="direction" class="form-label">Order</label>
                        <select class="form-select" id="direction" name="direction">
                            <option value="desc" {{ $direction == 'desc' ? 'selected' : '' }}>Desc</option>
                            <option value="asc" {{ $direction == 'asc' ? 'selected' : '' }}>Asc</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Bulk Actions Form -->
        <form id="bulk-action-form" action="{{ route('admin.telegram.users.bulk-action') }}" method="POST">
            @csrf
            
            <!-- Users Table -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-users me-1"></i>
                        Telegram Users ({{ $users->total() }})
                    </div>
                    <div class="d-flex">
                        <!-- Bulk Action Dropdown -->
                        <div class="dropdown me-2">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="bulkActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Bulk Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                                <li>
                                    <a class="dropdown-item bulk-action" href="#" data-action="message" data-bs-toggle="modal" data-bs-target="#bulkMessageModal">
                                        <i class="fas fa-envelope me-2"></i> Send Message
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item bulk-action" href="#" data-action="ban" data-bs-toggle="modal" data-bs-target="#confirmBulkModal">
                                        <i class="fas fa-ban me-2"></i> Ban Selected
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item bulk-action" href="#" data-action="unban" data-bs-toggle="modal" data-bs-target="#confirmBulkModal">
                                        <i class="fas fa-user-check me-2"></i> Unban Selected
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Export Button -->
                        <a href="#" class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel me-1"></i> Export
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th>User</th>
                                    <th>Telegram</th>
                                    <th>Status</th>
                                    <th>Coins</th>
                                    <th>Last Active</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="user-row {{ $user->is_banned ? 'banned' : '' }}">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input user-checkbox" type="checkbox" name="users[]" value="{{ $user->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3 rounded-circle bg-primary">
                                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->telegram_username)
                                                <a href="https://t.me/{{ $user->telegram_username }}" target="_blank" class="d-flex align-items-center text-decoration-none">
                                                    <i class="fab fa-telegram text-primary me-2"></i>
                                                    @{{ $user->telegram_username }}
                                                </a>
                                                <small class="text-muted d-block">ID: {{ $user->telegram_id }}</small>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fab fa-telegram text-primary me-2"></i>
                                                    ID: {{ $user->telegram_id }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->is_banned)
                                                <span class="badge text-bg-danger">
                                                    <i class="fas fa-ban me-1"></i> Banned
                                                </span>
                                            @elseif($user->last_login_at && $user->last_login_at >= \Carbon\Carbon::now()->subDays(7))
                                                <span class="badge text-bg-success">
                                                    <i class="fas fa-circle me-1"></i> Active
                                                </span>
                                            @else
                                                <span class="badge text-bg-secondary">
                                                    <i class="fas fa-circle me-1"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($user->coins) }}</td>
                                        <td>
                                            @if($user->last_login_at)
                                                {{ $user->last_login_at->diffForHumans() }}
                                                <small class="d-block text-muted">{{ $user->last_login_at->format('M d, Y') }}</small>
                                            @else
                                                <span class="text-muted">Never</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $user->created_at->format('M d, Y') }}
                                            <small class="d-block text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="userActions{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="userActions{{ $user->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.telegram.user', $user) }}">
                                                            <i class="fas fa-eye me-2"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#sendMessageModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                                            <i class="fas fa-envelope me-2"></i> Send Message
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @if($user->is_banned)
                                                        <li>
                                                            <form action="{{ route('admin.telegram.user.toggle-ban', $user) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="fas fa-user-check me-2"></i> Unban User
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <form action="{{ route('admin.telegram.user.toggle-ban', $user) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-ban me-2"></i> Ban User
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-search fa-2x mb-3"></i>
                                                <p>No users found matching the criteria.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
            
            <!-- Bulk Message Modal -->
            <div class="modal fade" id="bulkMessageModal" tabindex="-1" aria-labelledby="bulkMessageModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bulkMessageModalLabel">Send Message to Selected Users</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="bulk_message" class="form-label">Message</label>
                                <textarea class="form-control" id="bulk_message" name="bulk_message" rows="4" placeholder="Enter your message..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="bulk-message-btn">Send Messages</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bulk Confirmation Modal -->
            <div class="modal fade" id="confirmBulkModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmBulkTitle">Confirm Action</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="confirmBulkMessage">Are you sure you want to perform this action on the selected users?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger" id="confirm-bulk-action-btn">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="action" id="bulk-action-input" value="">
        </form>
        
        <!-- Send Message Modal -->
        <div class="modal fade" id="sendMessageModal" tabindex="-1" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" id="single-message-form" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="sendMessageModalLabel">Send Message</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="recipient" class="form-label">Recipient</label>
                                <input type="text" class="form-control" id="recipient" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter your message..."></textarea>
                                <div class="form-text">
                                    You can use HTML formatting: &lt;b&gt;bold&lt;/b&gt;, &lt;i&gt;italic&lt;/i&gt;, &lt;a href="..."&gt;link&lt;/a&gt;
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Parse Mode</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="parse_mode" id="parseHTML" value="HTML" checked>
                                    <label class="form-check-label" for="parseHTML">
                                        HTML
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="parse_mode" id="parseMarkdown" value="Markdown">
                                    <label class="form-check-label" for="parseMarkdown">
                                        Markdown
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });
    }
    
    // Handle bulk action selection
    const bulkActions = document.querySelectorAll('.bulk-action');
    const bulkActionInput = document.getElementById('bulk-action-input');
    const confirmBulkTitle = document.getElementById('confirmBulkTitle');
    const confirmBulkMessage = document.getElementById('confirmBulkMessage');
    
    bulkActions.forEach(action => {
        action.addEventListener('click', function(e) {
            e.preventDefault();
            const actionType = this.dataset.action;
            bulkActionInput.value = actionType;
            
            if (actionType === 'ban') {
                confirmBulkTitle.textContent = 'Ban Selected Users';
                confirmBulkMessage.textContent = 'Are you sure you want to ban all selected users?';
            } else if (actionType === 'unban') {
                confirmBulkTitle.textContent = 'Unban Selected Users';
                confirmBulkMessage.textContent = 'Are you sure you want to unban all selected users?';
            }
        });
    });
    
    // Handle submit for bulk actions
    const bulkMessageBtn = document.getElementById('bulk-message-btn');
    if (bulkMessageBtn) {
        bulkMessageBtn.addEventListener('click', function() {
            // Submit the form
            document.getElementById('bulk-action-form').submit();
        });
    }
    
    // Handle send message modal
    const sendMessageModal = document.getElementById('sendMessageModal');
    if (sendMessageModal) {
        sendMessageModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            
            const recipient = this.querySelector('#recipient');
            const form = this.querySelector('#single-message-form');
            
            recipient.value = userName;
            form.action = `/admin/telegram/users/${userId}/send-message`;
        });
    }
    
    // Auto-submit filter form on change
    const filterSelects = document.querySelectorAll('#status, #sort, #direction');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush 