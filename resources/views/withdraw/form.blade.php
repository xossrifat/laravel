@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">

    <h2 class="text-2xl font-bold mb-4 text-center">Withdraw Request</h2>
    
    <!-- Navigation links -->
    <div class="flex justify-center mb-6 space-x-2 text-sm">
        <a href="{{ route('withdraw.form') }}" class="px-3 py-1 bg-indigo-500 text-white rounded-full font-semibold">Request</a>
        <a href="{{ route('withdraw.history') }}" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full font-semibold">History</a>
        <a href="{{ route('withdraw.proof') }}" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full font-semibold">Proofs</a>
    </div>

    {{-- ✅ Success/Error Messages --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Exchange rate info --}}
    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <h3 class="text-sm font-semibold text-blue-800">Information</h3>
        <p class="text-xs text-blue-600 mt-1">Rate: {{ number_format($coinRate) }} coins = ৳1</p>
        <p class="text-xs text-blue-600 mt-1">Minimum withdrawal: ৳{{ number_format($minWithdraw) }} ({{ number_format($minCoins) }} coins)</p>
        <p class="text-xs text-blue-600 mt-1">Your balance: {{ number_format(auth()->user()->coins) }} coins</p>
    </div>

    <form action="{{ route('withdraw.submit') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700">Payment Method</label>
            <select name="payment_method" id="payment_method" class="w-full border rounded px-3 py-2" required>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method }}">{{ $method }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Payment Number</label>
            <input type="text" name="payment_number" class="w-full border rounded px-3 py-2" required>
            <p class="text-xs text-gray-500 mt-1">Enter your payment number (e.g., 01XXXXXXXXX)</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Amount (in coins)</label>
            <input type="number" name="amount" id="coinAmount" min="{{ $minCoins }}" class="w-full border rounded px-3 py-2" required>
            <p class="text-xs text-gray-500 mt-1">Minimum: {{ number_format($minCoins) }} coins</p>
            <p class="text-xs text-gray-500 mt-1">You will receive approximately: <span id="moneyAmount">৳0</span></p>
        </div>
        <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded w-full">
            Submit Request
        </button>
    </form>
</div>
@include('layouts.banner')
@push('scripts')
<script>
    // Calculate and display money amount based on coin input
    const coinInput = document.getElementById('coinAmount');
    const moneyDisplay = document.getElementById('moneyAmount');
    const coinRate = {{ $coinRate }};
    
    coinInput.addEventListener('input', function() {
        const coins = parseFloat(this.value) || 0;
        const money = (coins / coinRate).toFixed(2);
        moneyDisplay.textContent = '৳' + money;
    });
</script>
@endpush
@endsection
@include('partials.mobile-nav')