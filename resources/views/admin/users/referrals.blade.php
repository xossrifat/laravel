@extends('admin.layouts.app')

@section('title', 'User Referrals - ' . $user->name)
@section('header', 'User Referrals - ' . $user->name)

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-user-friends me-1"></i>
                        Referral Information
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Users
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">User Information</h5>
                                    <p class="mb-1"><strong>Name:</strong> {{ $user->name }}</p>
                                    <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p class="mb-1"><strong>Referral Code:</strong> <span class="badge bg-primary">{{ $user->referral_code }}</span></p>
                                    <p class="mb-1"><strong>Total Referrals:</strong> <span class="badge bg-success">{{ $user->referral_count }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Referred By</h5>
                                    @if($referrer)
                                        <p class="mb-1"><strong>Name:</strong> {{ $referrer->name }}</p>
                                        <p class="mb-1"><strong>Email:</strong> {{ $referrer->email }}</p>
                                        <p class="mb-0">
                                            <a href="{{ route('admin.users.referrals', $referrer->id) }}" class="btn btn-sm btn-outline-primary">
                                                View Referrer's Profile
                                            </a>
                                        </p>
                                    @else
                                        <p class="text-muted">This user was not referred by anyone.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <ul class="nav nav-tabs" id="referralTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="referred-tab" data-bs-toggle="tab" data-bs-target="#referred" type="button" role="tab" aria-controls="referred" aria-selected="true">
                                People Referred by {{ $user->name }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="rewards-tab" data-bs-toggle="tab" data-bs-target="#rewards" type="button" role="tab" aria-controls="rewards" aria-selected="false">
                                Referral Rewards Earned
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-3" id="referralTabsContent">
                        <!-- Users Referred Tab -->
                        <div class="tab-pane fade show active" id="referred" role="tabpanel" aria-labelledby="referred-tab">
                            @if($referrals->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Joined Date</th>
                                                <th>Coins Earned</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($referrals as $referral)
                                                <tr>
                                                    <td>{{ $referral->name }}</td>
                                                    <td>{{ $referral->email }}</td>
                                                    <td>{{ $referral->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        @php
                                                            $coinsEarned = $referral->referralRewards->sum('coins_earned');
                                                        @endphp
                                                        {{ $coinsEarned }} coins
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.users.referrals', $referral->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-user-friends"></i> View Referrals
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $referrals->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    {{ $user->name }} hasn't referred any users yet.
                                </div>
                            @endif
                        </div>
                        
                        <!-- Referral Rewards Tab -->
                        <div class="tab-pane fade" id="rewards" role="tabpanel" aria-labelledby="rewards-tab">
                            @if($referralRewards->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Referred User</th>
                                                <th>Coins Earned</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($referralRewards as $reward)
                                                <tr>
                                                    <td>{{ $reward->referral->name }}</td>
                                                    <td>{{ $reward->coins_earned }} coins</td>
                                                    <td>{{ $reward->created_at->format('M d, Y - h:i A') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.users.referrals', $reward->referral->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-user"></i> View User
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $referralRewards->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    {{ $user->name }} hasn't earned any referral rewards yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 