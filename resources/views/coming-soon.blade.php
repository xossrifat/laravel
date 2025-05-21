@extends('layouts.app')

@section('title', 'Coming Soon - Stay Tuned!')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-white via-blue-50 to-blue-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-md w-full text-center">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl p-8 mb-8">
            <div class="text-6xl text-blue-500 dark:text-blue-400 mb-6 animate-pulse">
                <i class="fas fa-rocket"></i>
            </div>
            
            <h1 class="text-4xl font-extrabold text-gray-800 dark:text-white mb-4">শীঘ্রই আসছে!</h1>
            <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-6">আমরা এখনো কাজ করছি</h2>
            
            <div class="mb-8 relative h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-blue-400 to-indigo-500 animate-progress rounded-full" style="width: 75%"></div>
            </div>
            
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                আমরা এই ফিচারটি নিয়ে অত্যন্ত উত্তেজিত! বর্তমানে আমরা আপনার জন্য একটি অসাধারণ অভিজ্ঞতা তৈরি করতে কঠোর পরিশ্রম করছি।
            </p>
            
            <div class="flex flex-col space-y-4">
                <a href="{{ url('/') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 px-6 rounded-lg font-medium shadow-md transition-all transform hover:-translate-y-1">
                    <i class="fas fa-home mr-2"></i> হোম পেজে ফিরে যান
                </a>
                
                <a href="javascript:history.back()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 py-3 px-6 rounded-lg font-medium transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> পূর্ববর্তী পেজে ফিরে যান
                </a>
            </div>
        </div>
        
        <!-- Email Notification -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4">আপডেট পেতে চান?</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">
                এই ফিচারটি যখন লাইভ হবে তখন আমরা আপনাকে জানাবো।
            </p>
            <form action="{{ route('subscribe.notification') }}" method="POST" class="flex flex-col space-y-3" id="notification-form">
                @csrf
                <div class="flex items-center">
                    <!-- Regular checkbox with standard appearance -->
                    <input type="checkbox" id="notify-me" name="notify" class="checkbox-visible mr-2 h-6 w-6 text-purple-600 border-2 border-purple-400 rounded focus:ring-2 focus:ring-purple-500 cursor-pointer">
                    
                    <label for="notify-me" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer select-none">
                        আমাকে বিজ্ঞপ্তি দিন
                    </label>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 text-white py-2 px-4 rounded-lg font-medium shadow-md transition-all transform hover:-translate-y-1">
                    <i class="fas fa-bell mr-2"></i> সেভ করুন
                </button>
            </form>
            
            <div id="success-message" class="hidden mt-3 text-sm text-green-600 bg-green-100 p-2 rounded">
                আপনার অনুরোধ সফলভাবে নথিভুক্ত করা হয়েছে!
            </div>
        </div>
        
        <div class="mt-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                কোন প্রশ্ন আছে? <a href="{{ route('support.create') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">আমাদের সাথে যোগাযোগ করুন</a>
            </p>
        </div>
    </div>
</div>
@include('partials.mobile-nav')

<style>
@keyframes progress {
    0% { width: 65%; }
    50% { width: 85%; }
    100% { width: 65%; }
}
.animate-progress {
    animation: progress 5s ease-in-out infinite;
}

/* Standard checkbox styling - will override any custom styling */
.checkbox-visible {
    appearance: none !important;
    width: 24px !important;
    height: 24px !important;
    border: 2px solid #673de6 !important;
    background-color: white !important;
}

.checkbox-visible:checked {
    background-color: #673de6 !important;
    border-color: #673de6 !important;
}

/* Pulse animation for checkbox */
@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
    }
}

.animate-pulse {
    animation: pulse 1s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('notification-form');
    const checkbox = document.getElementById('notify-me');
    const submitButton = form.querySelector('button[type="submit"]');
    
    if (form) {
        // Add a visible indicator when checkbox changes
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                submitButton.classList.remove('opacity-50');
                submitButton.disabled = false;
            } else {
                submitButton.classList.add('opacity-50');
                submitButton.disabled = true;
            }
        });
        
        // Initialize button state
        if (!checkbox.checked) {
            submitButton.classList.add('opacity-50');
            submitButton.disabled = true;
        }
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!checkbox.checked) {
                // Make the checkbox flash to draw attention
                checkbox.classList.add('animate-pulse');
                setTimeout(() => {
                    checkbox.classList.remove('animate-pulse');
                }, 1000);
                
                // Show a more visible error message
                const errorMsg = document.createElement('div');
                errorMsg.className = 'text-sm text-red-600 bg-red-100 p-2 rounded mt-2';
                errorMsg.textContent = 'আপডেট পেতে বিজ্ঞপ্তি চেকবক্সে টিক দিন!';
                
                // Remove any existing error message
                const existingError = form.querySelector('.text-red-600');
                if (existingError) {
                    form.removeChild(existingError);
                }
                
                form.appendChild(errorMsg);
                
                // Remove error after 3 seconds
                setTimeout(() => {
                    if (errorMsg.parentNode === form) {
                        form.removeChild(errorMsg);
                    }
                }, 3000);
                
                return;
            }
            
            // Form would normally submit to the server here
            // For now, just show the success message
            document.getElementById('success-message').classList.remove('hidden');
            
            // Hide success message after 3 seconds
            setTimeout(function() {
                document.getElementById('success-message').classList.add('hidden');
                checkbox.checked = false;
                submitButton.classList.add('opacity-50');
                submitButton.disabled = true;
            }, 3000);
        });
    }
});
</script>
@endsection 