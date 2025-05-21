<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index()
    {
        $transactions = Withdraw::with('user')
            ->latest()
            ->paginate(15);

        $stats = [
            'total_pending' => Withdraw::where('status', 'pending')->count(),
            'total_approved' => Withdraw::where('status', 'completed')->count(),
            'total_rejected' => Withdraw::where('status', 'rejected')->count(),
            'amount_pending' => Withdraw::where('status', 'pending')->sum('amount'),
            'amount_paid' => Withdraw::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Show pending withdrawal requests.
     */
    public function requests()
    {
        $requests = Withdraw::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.transactions.requests', compact('requests'));
    }

    /**
     * Approve a withdrawal request.
     */
    public function approve(Withdraw $request)
    {
        $request->update([
            'status' => 'completed',
            'processed_at' => now(),
            'processed_by' => auth()->id()
        ]);

        // You might want to trigger a notification here
        // $request->user->notify(new WithdrawalApproved($request));

        return back()->with('success', 'Withdrawal request approved successfully.');
    }

    /**
     * Reject a withdrawal request.
     */
    public function reject(Request $httpRequest, Withdraw $request)
    {
        $validated = $httpRequest->validate([
            'reason' => 'required|string|max:255'
        ]);

        // Get the user who made the withdrawal request
        $user = $request->user;
        
        // Store the amount of coins to refund
        $coinsToRefund = $request->coins_used;

        $request->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['reason'],
            'processed_at' => now(),
            'processed_by' => auth()->id()
        ]);

        // Refund the coins to the user - use coins_used field
        if ($user && $coinsToRefund > 0) {
            $user->increment('coins', $coinsToRefund);
            \Log::info("Refunded {$coinsToRefund} coins to user {$user->id} ({$user->name})");
        } else {
            \Log::error("Failed to refund coins for withdrawal {$request->id}. User: " . ($user ? $user->id : 'null') . ", Coins: {$coinsToRefund}");
        }

        return back()->with('success', 'Withdrawal request rejected and coins refunded.');
    }

    /**
     * Show transaction history.
     */
    public function history()
    {
        $history = Withdraw::with('user')
            ->whereIn('status', ['completed', 'rejected'])
            ->latest()
            ->paginate(15);

        $monthlyStats = Withdraw::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as total_requests,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as total_paid
        ')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->limit(12)
        ->get();

        return view('admin.transactions.history', compact('history', 'monthlyStats'));
    }

    /**
     * Show withdrawal configuration.
     */
    public function config()
    {
        $settings = [
            'min_withdraw' => Setting::where('key', 'min_withdraw_amount')->first()?->value ?? 10,
            'coin_rate' => Setting::where('key', 'coin_rate')->first()?->value ?? 1000,
            'payment_methods' => Setting::where('key', 'payment_methods')->first()?->value ?? '[]',
            'processing_time' => Setting::where('key', 'withdrawal_processing_time')->first()?->value ?? '24-48 hours',
        ];

        return view('admin.transactions.config', compact('settings'));
    }

    /**
     * Update withdrawal configuration.
     */
    public function updateConfig(Request $request)
    {
        // Debug incoming data
        \Log::info('Transaction config update request:', $request->all());

        $validated = $request->validate([
            'min_withdraw' => 'required|numeric|min:1',
            'coin_rate' => 'required|numeric|min:1',
            'payment_methods' => 'required|array',
            'payment_methods.*' => 'required|string',
            'processing_time' => 'required|string|max:255',
        ]);

        \Log::info('Validated data:', $validated);

        try {
            // Check if the settings table exists
            if (!\Schema::hasTable('settings')) {
                \Log::error('Settings table does not exist');
                return back()->withErrors(['error' => 'Settings table does not exist']);
            }

            // Log more detailed information before updates
            \Log::info('Payment methods before update: ' . json_encode(Setting::where('key', 'payment_methods')->first()?->value));

            // Backup direct method using query builder
            \DB::table('settings')->updateOrInsert(
                ['key' => 'min_withdraw_amount'],
                ['key' => 'min_withdraw_amount', 'value' => $validated['min_withdraw']]
            );

            \DB::table('settings')->updateOrInsert(
                ['key' => 'coin_rate'],
                ['key' => 'coin_rate', 'value' => $validated['coin_rate']]
            );

            $paymentMethodsJson = json_encode($validated['payment_methods']);
            \Log::info('Saving payment methods: ' . $paymentMethodsJson);
            
            \DB::table('settings')->updateOrInsert(
                ['key' => 'payment_methods'],
                ['key' => 'payment_methods', 'value' => $paymentMethodsJson]
            );

            \DB::table('settings')->updateOrInsert(
                ['key' => 'withdrawal_processing_time'],
                ['key' => 'withdrawal_processing_time', 'value' => $validated['processing_time']]
            );

            // Verify that payment methods were saved
            $savedPaymentMethods = Setting::where('key', 'payment_methods')->first()?->value;
            \Log::info('Payment methods after update: ' . $savedPaymentMethods);
            
            \Log::info('Settings updated successfully using DB::table');
            
            return back()->with('success', 'Withdrawal configuration updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating settings: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()]);
        }
    }
} 