@extends('admin.layouts.app')

@section('title', 'Spin Configuration')
@section('header', 'Spin Configuration')

@section('content')
<div class="space-y-6">
    <!-- Daily Spin Limit -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Daily Spin Limit</h3>
            <p class="mt-1 text-sm text-gray-500">Set the maximum number of spins allowed per user per day.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.spin-config.limits.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="max-w-xs">
                    <label for="daily_limit" class="block text-sm font-medium text-gray-700">Daily Limit</label>
                    <input type="number" name="daily_limit" id="daily_limit" value="{{ $dailyLimit }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        min="1" required>
                </div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Limit
                </button>
            </form>
        </div>
    </div>

    <!-- Spin Rewards -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Spin Rewards</h3>
            <p class="mt-1 text-sm text-gray-500">Configure the rewards and their probabilities for the spin wheel.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.spin-config.rewards.update') }}" method="POST" id="rewardsForm">
                @csrf
                <div class="space-y-4" id="rewardsContainer">
                    @foreach($rewards as $index => $reward)
                    <div class="flex items-center gap-4 reward-item">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Label</label>
                            <input type="text" name="rewards[{{ $index }}][label]" value="{{ $reward->label }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Coins</label>
                            <input type="number" name="rewards[{{ $index }}][coins]" value="{{ $reward->coins }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required min="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Probability (%)</label>
                            <input type="number" name="rewards[{{ $index }}][probability]" value="{{ $reward->probability }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required min="0" max="100" step="0.1">
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="removeReward(this)"
                                class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 space-x-2">
                    <button type="button" onclick="addReward()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Add Reward
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Rewards
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Reward Distribution</h3>
            <p class="mt-1 text-sm text-gray-500">Visual representation of reward probabilities.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="h-64">
                <canvas id="probabilityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Spin Ads Configuration -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Spin Ads</h3>
            <p class="mt-1 text-sm text-gray-500">Configure ads that will appear when users click the spin button.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.spin-config.ads.update') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Enable Spin Ads -->
                <div class="flex items-start mb-4">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="spin_ads_enabled" id="spin_ads_enabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            {{ isset($spinAdsEnabled) && $spinAdsEnabled ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="spin_ads_enabled" class="font-medium text-gray-700">Enable Ads on Spin</label>
                        <p class="text-gray-500">When enabled, ads will be displayed when users click the spin button.</p>
                    </div>
                </div>

                <!-- Ad URLs -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ad URLs</label>
                    <p class="text-xs text-gray-500 mb-2">Add one or more ad script URLs. One will be randomly selected when the spin button is clicked.</p>
                    
                    <div class="space-y-2" id="adUrlsContainer">
                        @if(isset($spinAdUrls) && !empty($spinAdUrls))
                            @foreach($spinAdUrls as $index => $url)
                                <div class="flex items-center gap-2 ad-url-item">
                                    <input type="text" name="spin_ad_urls[]" value="{{ $url }}" 
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           placeholder="<script async src='//example.com/ad-code.js'></script>">
                                    <button type="button" class="remove-ad-url inline-flex items-center p-2 border border-transparent rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center gap-2 ad-url-item">
                                <input type="text" name="spin_ad_urls[]" 
                                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="<script async src='//example.com/ad-code.js'></script>">
                                <button type="button" class="remove-ad-url inline-flex items-center p-2 border border-transparent rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <button type="button" id="addAdUrlBtn" class="mt-2 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add URL
                    </button>
                </div>

                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Spin Ads
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Function to add new reward
    function addReward() {
        const container = document.getElementById('rewardsContainer');
        const index = container.children.length;
        const template = `
            <div class="flex items-center gap-4 reward-item">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Label</label>
                    <input type="text" name="rewards[${index}][label]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Coins</label>
                    <input type="number" name="rewards[${index}][coins]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Probability (%)</label>
                    <input type="number" name="rewards[${index}][probability]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        required min="0" max="100" step="0.1">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removeReward(this)"
                        class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        updateChart();
    }

    // Function to remove reward
    function removeReward(button) {
        button.closest('.reward-item').remove();
        updateChart();
    }

    // Initialize chart
    let probabilityChart;

    function updateChart() {
        const rewards = Array.from(document.querySelectorAll('.reward-item')).map(item => ({
            label: item.querySelector('input[name*="[label]"]').value,
            probability: parseFloat(item.querySelector('input[name*="[probability]"]').value) || 0
        }));

        if (probabilityChart) {
            probabilityChart.destroy();
        }

        const ctx = document.getElementById('probabilityChart').getContext('2d');
        probabilityChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: rewards.map(r => r.label),
                datasets: [{
                    data: rewards.map(r => r.probability),
                    backgroundColor: [
                        '#4F46E5', '#10B981', '#F59E0B', '#EF4444',
                        '#6366F1', '#8B5CF6', '#EC4899', '#14B8A6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    }

    // Initialize chart on page load
    document.addEventListener('DOMContentLoaded', updateChart);

    // Update chart when form inputs change
    document.getElementById('rewardsForm').addEventListener('input', updateChart);

    // Ad URL functionality using simpler jQuery approach
    $(document).ready(function() {
        // Add URL button click handler
        $("#addAdUrlBtn").click(function() {
            const newItem = `
                <div class="flex items-center gap-2 ad-url-item">
                    <input type="text" name="spin_ad_urls[]" 
                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="<script async src='//example.com/ad-code.js'></script>">
                    <button type="button" class="remove-ad-url inline-flex items-center p-2 border border-transparent rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            $("#adUrlsContainer").append(newItem);
        });
        
        // Use document-level delegation for remove buttons
        $(document).on("click", ".remove-ad-url", function() {
            const container = $("#adUrlsContainer");
            const item = $(this).closest('.ad-url-item');
            
            if (container.find('.ad-url-item').length > 1) {
                item.remove();
            } else {
                item.find('input').val('');
            }
        });
    });
</script>
@endpush
@endsection 