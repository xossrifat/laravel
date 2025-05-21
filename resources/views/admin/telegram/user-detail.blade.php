@extends('layouts.admin')

@section('title', 'Telegram User Details')

@push('styles')
<style>
    .user-header {
        background: linear-gradient(to right, #0088cc, #005580);
        padding: 2rem;
        border-radius: 0.5rem;
        color: white;
        margin-bottom: 2rem;
    }
    
    .telegram-id-badge {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50rem;
        padding: 0.35em 0.75em;
        font-size: 0.85em;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 42px;
        background-color: white;
        color: #0088cc;
        border-radius: 50%;
        margin-right: 1.5rem;
    }
    
    .activity-item {
        border-left: 2px solid #e9ecef;
        padding-left: 1.5rem;
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .activity-item:last-child {
        padding-bottom: 0;
    }
    
    .activity-item::before {
        content: '';
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #0d6efd;
        position: absolute;
        left: -7px;
        top: 6px;
    }
    
    .activity-item.spin::before {
        background-color: #0dcaf0;
    }
    
    .activity-item.video::before {
        background-color: #20c997;
    }
    
    .activity-item.withdraw::before {
        background-color: #ffc107;
    }
    
    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
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
    
    .nav-pills .nav-link.active {
        background-color: #0088cc;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center my-4">
            <h1>Telegram User Details</h1>
            <a href="{{ route('admin.telegram.users') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Users
            </a>
        </div>
        
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
        
        <!-- User Profile Header -->
        <div class="user-header">
            <div class="d-flex align-items-center">
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h2 class="mb-0 d-flex align-items-center">
                        {{ $user->name }}
                        
                        @if($user->is_banned)
                            <span class="ms-2 badge bg-danger">Banned</span>
                        @elseif($user->last_login_at && $user->last_login_at >= \Carbon\Carbon::now()->subDays(7))
                            <span class="ms-2 badge bg-success">Active</span>
                        @else
                            <span class="ms-2 badge bg-secondary">Inactive</span>
                        @endif
                    </h2>
                    
                    <div class="mt-2 d-flex align-items-center">
                        <i class="fab fa-telegram fa-lg me-2"></i>
                        @if($user->telegram_username)
                            <a href="https://t.me/{{ $user->telegram_username }}" target="_blank" class="text-white text-decoration-none">
                                @{{ $user->telegram_username }}
                            </a>
                        @else
                            <span>No username</span>
                        @endif
                        <span class="telegram-id-badge ms-2">ID: {{ $user->telegram_id }}</span>
                    </div>
                    
                    <div class="mt-3">
                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#sendMessageModal">
                            <i class="fas fa-paper-plane me-2"></i> Send Message
                        </button>
                        
                        <form action="{{ route('admin.telegram.user.toggle-ban', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $user->is_banned ? 'btn-success' : 'btn-danger' }}" onclick="return confirm('Are you sure you want to {{ $user->is_banned ? 'unban' : 'ban' }} this user?')">
                                <i class="fas {{ $user->is_banned ? 'fa-unlock' : 'fa-ban' }}"></i>
                                {{ $user->is_banned ? 'Unban User' : 'Ban User' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-4">
                <!-- User Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-user me-1"></i>
                        User Information
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td class="fw-bold">ID</td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Coins</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-medium">{{ number_format($user->coins) }}</span>
                                        <a href="{{ route('admin.users.coins', $user) }}" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status</td>
                                <td>
                                    @if($user->is_banned)
                                        <span class="status-indicator status-banned"></span>
                                        Banned
                                    @elseif($user->last_login_at && $user->last_login_at >= \Carbon\Carbon::now()->subDays(7))
                                        <span class="status-indicator status-active"></span>
                                        Active
                                    @else
                                        <span class="status-indicator status-inactive"></span>
                                        Inactive
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Last Login</td>
                                <td>
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->format('M d, Y H:i') }}
                                        <div class="small text-muted">{{ $user->last_login_at->diffForHumans() }}</div>
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Registered</td>
                                <td>
                                    {{ $user->created_at->format('M d, Y H:i') }}
                                    <div class="small text-muted">{{ $user->created_at->diffForHumans() }}</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Telegram Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fab fa-telegram me-1"></i>
                        Telegram Information
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.telegram.user.update-info', $user) }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="telegram_username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input 
                                        type="text" 
                                        class="form-control @error('telegram_username') is-invalid @enderror" 
                                        id="telegram_username" 
                                        name="telegram_username" 
                                        value="{{ old('telegram_username', $user->telegram_username) }}"
                                    >
                                    @error('telegram_username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telegram_id" class="form-label">Telegram ID</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="telegram_id" 
                                    value="{{ $user->telegram_id }}"
                                    readonly
                                >
                            </div>
                            
                            <div class="mb-3">
                                <label for="telegram_notes" class="form-label">Notes</label>
                                <textarea 
                                    class="form-control @error('telegram_notes') is-invalid @enderror" 
                                    id="telegram_notes" 
                                    name="telegram_notes"
                                    rows="3"
                                >{{ old('telegram_notes', $user->notes) }}</textarea>
                                @error('telegram_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Quick Stats Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-1"></i>
                        Activity Summary
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="h3 mb-0">{{ $spins->count() }}</div>
                                <div class="small text-muted">Spins</div>
                            </div>
                            <div class="col-4">
                                <div class="h3 mb-0">{{ $videoWatches->count() }}</div>
                                <div class="small text-muted">Videos</div>
                            </div>
                            <div class="col-4">
                                <div class="h3 mb-0">{{ $withdrawals->count() }}</div>
                                <div class="small text-muted">Withdrawals</div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.spins', $user) }}" class="btn btn-sm btn-info text-white">
                                <i class="fas fa-dharmachakra me-2"></i> All Spins
                            </a>
                            <a href="{{ route('admin.users.videos', $user) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-play me-2"></i> All Videos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="col-lg-8">
                <!-- Activity Tab Navigation -->
                <div class="card mb-4">
                    <div class="card-header">
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link active" id="activity-tab" data-bs-toggle="tab" href="#activity">Recent Activity</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="withdrawals-tab" data-bs-toggle="tab" href="#withdrawals">Withdrawals</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="messages-tab" data-bs-toggle="tab" href="#messages">Message History</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane fade show active" id="activity">
                                <h5 class="card-title mb-4">Recent Activity</h5>
                                
                                @if($spins->isEmpty() && $videoWatches->isEmpty())
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-clock fa-3x mb-3"></i>
                                        <p>No recent activity found</p>
                                    </div>
                                @else
                                    <div class="activity-timeline">
                                        @foreach(collect($spins)->concat($videoWatches)->sortByDesc('created_at')->take(10) as $activity)
                                            @if(get_class($activity) === 'App\Models\Spin')
                                                <div class="activity-item spin">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <h6 class="mb-1">Spin Reward</h6>
                                                            <p class="mb-0">
                                                                Won {{ $activity->coins_won }} coins
                                                                @if($activity->reward_id)
                                                                    from {{ $activity->reward->label ?? 'a reward' }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="text-end">
                                                            <small class="text-muted">{{ $activity->created_at->format('M d, Y H:i') }}</small>
                                                            <div>{{ $activity->created_at->diffForHumans() }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif(get_class($activity) === 'App\Models\VideoWatch')
                                                <div class="activity-item video">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <h6 class="mb-1">Watched Video</h6>
                                                            <p class="mb-0">
                                                                Earned {{ $activity->coins_earned }} coins
                                                                @if($activity->video_ad_id)
                                                                    from video #{{ $activity->video_ad_id }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="text-end">
                                                            <small class="text-muted">{{ $activity->created_at->format('M d, Y H:i') }}</small>
                                                            <div>{{ $activity->created_at->diffForHumans() }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Withdrawals Tab -->
                            <div class="tab-pane fade" id="withdrawals">
                                <h5 class="card-title mb-4">Withdrawal History</h5>
                                
                                @if($withdrawals->isEmpty())
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                                        <p>No withdrawals found</p>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($withdrawals as $withdrawal)
                                                    <tr>
                                                        <td>
                                                            {{ $withdrawal->created_at->format('M d, Y') }}
                                                            <small class="d-block text-muted">{{ $withdrawal->created_at->diffForHumans() }}</small>
                                                        </td>
                                                        <td>
                                                            <div class="fw-medium">{{ $withdrawal->coins }} coins</div>
                                                            <small class="text-muted">≈ {{ $withdrawal->amount ?? 'N/A' }}</small>
                                                        </td>
                                                        <td>
                                                            {{ $withdrawal->method ?? 'Unknown' }}
                                                            @if($withdrawal->account_number)
                                                                <small class="d-block text-muted">{{ $withdrawal->account_number }}</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($withdrawal->status == 'pending')
                                                                <span class="badge bg-warning text-dark">Pending</span>
                                                            @elseif($withdrawal->status == 'approved')
                                                                <span class="badge bg-success">Approved</span>
                                                            @elseif($withdrawal->status == 'rejected')
                                                                <span class="badge bg-danger">Rejected</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ ucfirst($withdrawal->status) }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Messages Tab -->
                            <div class="tab-pane fade" id="messages">
                                <h5 class="card-title mb-4">Message History</h5>
                                
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-envelope-open-text fa-3x mb-3"></i>
                                    <p>Message history functionality coming soon</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Send Message Modal -->
        <div class="modal fade" id="sendMessageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.telegram.user.send-message', $user) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Send Message to {{ $user->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" placeholder="Enter your message...">{{ old('message') }}</textarea>
                                <div class="form-text">
                                    You can use HTML formatting: &lt;b&gt;bold&lt;/b&gt;, &lt;i&gt;italic&lt;/i&gt;, &lt;a href="..."&gt;link&lt;/a&gt;
                                </div>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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