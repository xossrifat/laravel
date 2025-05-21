@extends('layouts.admin')

@section('title', 'Telegram Dashboard')

@push('styles')
<style>
    .stat-card {
        transition: transform .2s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Telegram Users Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Telegram Dashboard</li>
        </ol>
        
        <!-- Stats Overview -->
        <div class="row">
            <!-- Total Telegram Users -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4 stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">{{ number_format($totalTelegramUsers) }}</h3>
                                <div class="small">Telegram Users</div>
                            </div>
                            <div class="h1">
                                <i class="fab fa-telegram-plane"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <div class="small text-white">{{ $telegramPercentage }}% of all users</div>
                        <a class="small text-white" href="{{ route('admin.telegram.users') }}">View All</a>
                    </div>
                </div>
            </div>

            <!-- Active Last Week -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4 stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">{{ number_format($lastWeekActive) }}</h3>
                                <div class="small">Active Last 7 Days</div>
                            </div>
                            <div class="h1">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <div class="small text-white">
                            {{ $totalTelegramUsers > 0 ? round(($lastWeekActive / $totalTelegramUsers) * 100) : 0 }}% active rate
                        </div>
                        <a class="small text-white" href="{{ route('admin.telegram.users', ['status' => 'active']) }}">View Active</a>
                    </div>
                </div>
            </div>

            <!-- New Users Today -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white mb-4 stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">{{ count($newUsersDaily) > 0 ? $newUsersDaily[count($newUsersDaily) - 1]['count'] : 0 }}</h3>
                                <div class="small">New Users Today</div>
                            </div>
                            <div class="h1">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        @php
                            $yesterdayCount = count($newUsersDaily) > 1 ? $newUsersDaily[count($newUsersDaily) - 2]['count'] : 0;
                            $todayCount = count($newUsersDaily) > 0 ? $newUsersDaily[count($newUsersDaily) - 1]['count'] : 0;
                            $change = $yesterdayCount > 0 ? round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100) : 0;
                        @endphp
                        <div class="small text-white">
                            @if($change > 0)
                                <i class="fas fa-arrow-up"></i> {{ $change }}% from yesterday
                            @elseif($change < 0)
                                <i class="fas fa-arrow-down"></i> {{ abs($change) }}% from yesterday
                            @else
                                Same as yesterday
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Send Message Button -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-dark text-white mb-4 stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">Messaging</h3>
                                <div class="small">Broadcast Messages</div>
                            </div>
                            <div class="h1">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a href="{{ route('admin.telegram.messages') }}" class="btn btn-outline-light btn-sm w-100">
                            <i class="fas fa-paper-plane me-2"></i> 
                            Send Messages
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Daily New Users Chart -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-line me-1"></i>
                        New Telegram Users (Last 7 Days)
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="dailyUsersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Earners -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-medal me-1"></i>
                        Top Telegram Earners
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Coins</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topEarners as $index => $user)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2 bg-primary text-white rounded-circle">
                                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $user->name }}</div>
                                                        <small class="text-muted">@{{ $user->telegram_username ?: 'No username' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">{{ number_format($user->coins) }}</td>
                                            <td>
                                                <a href="{{ route('admin.telegram.user', $user) }}" class="btn btn-sm btn-link" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No users found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Data Charts -->
        <div class="row">
            <!-- Weekly & Monthly Charts -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Weekly New Users (10 Weeks)
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="weeklyUsersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Monthly New Users (6 Months)
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyUsersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-bolt me-1"></i>
                        Quick Actions
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.telegram.users') }}" class="btn btn-primary w-100 py-3">
                                    <i class="fas fa-users fa-2x mb-2"></i><br>
                                    Manage Users
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.telegram.messages') }}" class="btn btn-success w-100 py-3">
                                    <i class="fas fa-comment-alt fa-2x mb-2"></i><br>
                                    Send Messages
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.telegram.config') }}" class="btn btn-info text-white w-100 py-3">
                                    <i class="fas fa-cog fa-2x mb-2"></i><br>
                                    Bot Configuration
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-100 py-3">
                                    <i class="fas fa-user-cog fa-2x mb-2"></i><br>
                                    All Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format data for charts
    const dailyLabels = @json(array_column($newUsersDaily, 'label'));
    const dailyCounts = @json(array_column($newUsersDaily, 'count'));
    
    const weeklyLabels = @json(array_column($newUsersWeekly, 'label'));
    const weeklyCounts = @json(array_column($newUsersWeekly, 'count'));
    
    const monthlyLabels = @json(array_column($newUsersMonthly, 'label'));
    const monthlyCounts = @json(array_column($newUsersMonthly, 'count'));

    // Daily Users Chart
    const dailyCtx = document.getElementById('dailyUsersChart').getContext('2d');
    const dailyChart = new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'New Users',
                data: dailyCounts,
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 2,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: 'rgba(13, 110, 253, 1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    padding: 10,
                    titleFont: {
                        size: 14,
                    },
                    bodyFont: {
                        size: 14
                    },
                    callbacks: {
                        label: function(context) {
                            return 'New users: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Weekly Users Chart
    const weeklyCtx = document.getElementById('weeklyUsersChart').getContext('2d');
    const weeklyChart = new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: weeklyLabels,
            datasets: [{
                label: 'New Users',
                data: weeklyCounts,
                backgroundColor: 'rgba(25, 135, 84, 0.7)',
                borderColor: 'rgba(25, 135, 84, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Monthly Users Chart
    const monthlyCtx = document.getElementById('monthlyUsersChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'New Users',
                data: monthlyCounts,
                backgroundColor: 'rgba(13, 202, 240, 0.7)',
                borderColor: 'rgba(13, 202, 240, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>

<style>
    /* Custom avatar style */
    .avatar-sm {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }
</style>
@endpush 