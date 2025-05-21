@extends('admin.layouts.app')

@section('title', 'Transaction Configuration')
@section('header', 'Transaction Configuration')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Withdrawal Settings</h3>
        <p class="mt-1 text-sm text-gray-500">Configure withdrawal limits, coin rates, and payment methods.</p>
    </div>
    <div class="px-4 py-5 sm:p-6">
        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-md p-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-md p-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.transactions.config.direct-update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- Minimum Withdrawal Amount -->
                <div>
                    <label for="min_withdraw" class="block text-sm font-medium text-gray-700">Minimum Withdrawal Amount (TK)</label>
                    <div class="mt-1">
                        <input type="number" name="min_withdraw" id="min_withdraw" min="1" step="1" 
                            value="{{ old('min_withdraw', $settings['min_withdraw']) }}" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('min_withdraw') border-red-500 @enderror">
                    </div>
                    @error('min_withdraw')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Minimum amount in TK that users can withdraw.</p>
                </div>

                <!-- Coin Rate -->
                <div>
                    <label for="coin_rate" class="block text-sm font-medium text-gray-700">Coin Rate (Coins per TK)</label>
                    <div class="mt-1">
                        <input type="number" name="coin_rate" id="coin_rate" min="1" step="1" 
                            value="{{ old('coin_rate', $settings['coin_rate']) }}" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('coin_rate') border-red-500 @enderror">
                    </div>
                    @error('coin_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">How many coins are equivalent to 1 TK.</p>
                </div>

                <!-- Payment Methods -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Methods</label>
                    <div id="payment-methods" class="space-y-3">
                        @php 
                            $methods = old('payment_methods', json_decode($settings['payment_methods'] ?? '[]', true)); 
                            if (empty($methods)) $methods = [''];
                        @endphp
                        @foreach($methods as $index => $method)
                            <div class="flex items-center space-x-2 payment-method">
                                <input type="text" name="payment_methods[]" value="{{ $method }}"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('payment_methods.'.$index) border-red-500 @enderror">
                                <button type="button" onclick="removePaymentMethod(this)" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @error('payment_methods.'.$index)
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endforeach
                    </div>
                    @error('payment_methods')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <button type="button" onclick="addPaymentMethod()" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i> Add Payment Method
                    </button>
                </div>

                <!-- Processing Time -->
                <div>
                    <label for="processing_time" class="block text-sm font-medium text-gray-700">Processing Time</label>
                    <div class="mt-1">
                        <input type="text" name="processing_time" id="processing_time"
                            value="{{ old('processing_time', $settings['processing_time']) }}" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('processing_time') border-red-500 @enderror">
                    </div>
                    @error('processing_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Expected time to process withdrawals (e.g. "24-48 hours").</p>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function addPaymentMethod() {
        const container = document.getElementById('payment-methods');
        const methodDiv = document.createElement('div');
        methodDiv.className = 'flex items-center space-x-2 payment-method';
        methodDiv.innerHTML = `
            <input type="text" name="payment_methods[]" placeholder="Payment method" 
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            <button type="button" onclick="removePaymentMethod(this)" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(methodDiv);
    }

    function removePaymentMethod(button) {
        const methodDiv = button.closest('.payment-method');
        const container = document.getElementById('payment-methods');
        
        // Don't remove if it's the last one
        if (container.children.length > 1) {
            methodDiv.remove();
        }
    }
</script>
@endpush
@endsection
