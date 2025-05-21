<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Withdraw;
use App\Models\Setting;
use Illuminate\Http\Request;


class WithdrawController extends Controller
{
    public function history()
    {
        $user = Auth::user();

        $requests = $user->withdrawals()->latest()->get();

        return view('withdraw.history', compact('requests'));
    }

    public function showForm()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            // Redirect to login page with a message
            return redirect()->route('login')->with('message', 'Please login to withdraw your earnings.');
        }
        
        $user = Auth::user();
        
        $coinRate = Setting::get('coin_rate', 1000); // Default: 1000 coins = 1 TK
        $minWithdraw = Setting::get('min_withdraw_amount', 10); // Default: 10 TK minimum
        
        // Calculate minimum coins needed
        $minCoins = $minWithdraw * $coinRate;
        
        // Get payment methods from settings using the JSON helper
        $paymentMethods = Setting::getJson('payment_methods', ['bKash']);
        
        \Log::info('Payment methods for withdrawal form: ' . json_encode($paymentMethods));
        
        return view('withdraw.form', compact('coinRate', 'minWithdraw', 'minCoins', 'paymentMethods'));
    }
    
    public function submit(Request $request)
    {
        $user = Auth::user();
        
        // Get coin rate from settings
        $coinRate = Setting::get('coin_rate', 1000); // Default: 1000 coins = 1 TK
        $minWithdraw = Setting::get('min_withdraw_amount', 10); // Default minimum amount in TK
        
        // Calculate minimum coins required
        $minCoins = $minWithdraw * $coinRate;
        
        $request->validate([
            'payment_method' => 'required|string',
            'payment_number' => 'required|string|min:11|max:14',
            'amount' => 'required|integer|min:' . $minCoins,
        ]);

        $amount = $request->amount;

        // Check if user has enough coins
        if ($user->coins < $amount) {
            return back()->with('error', 'You do not have enough coins to withdraw.');
        }

        // Calculate real money amount (TK)
        $moneyAmount = $amount / $coinRate;
        
        if ($moneyAmount < $minWithdraw) {
            return back()->with('error', "Minimum withdraw amount is {$minWithdraw} TK ({$minCoins} coins).");
        }

        // Deduct coins
        $user->coins -= $amount;
        $user->save();

        // Create withdrawal request that will show up for admins
        Withdraw::create([
            'user_id' => $user->id,
            'amount' => $moneyAmount, // Money amount in TK
            'coins_used' => $amount, // Coins used
            'payment_method' => $request->payment_method, // Use selected payment method
            'payment_number' => $request->payment_number, // Use payment number field
            'status' => 'pending',
        ]);

        return back()->with('success', 'Withdraw request submitted successfully. You will be notified when processed.');
    }

    public function proof()
    {
        // Get all completed withdrawals to show as proof
        $completedWithdrawals = Withdraw::with('user')
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);
            
        return view('withdraw.proof', compact('completedWithdrawals'));
    }
}