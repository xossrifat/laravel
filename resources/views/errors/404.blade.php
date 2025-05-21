@extends('layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <div class="text-7xl text-amber-500 mb-6 inline-block">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        
        <h1 class="text-4xl font-extrabold text-gray-800 dark:text-gray-200 mb-4">404</h1>
        <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-6">পেজ খুঁজে পাওয়া যায়নি!</h2>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-8 mb-8">
            <p class="text-gray-600 dark:text-gray-400 mb-6">আপনি যে পেজটি খুঁজছেন সেটি এখানে নেই। এটি হয়তো সরানো হয়েছে, নাম পরিবর্তন করা হয়েছে, অথবা অস্থায়ীভাবে অনুপলব্ধ।</p>
            
            <div class="flex flex-col space-y-4">
                <a href="{{ url('/') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 px-6 rounded-lg font-medium shadow-md transition-all transform hover:-translate-y-1">
                    <i class="fas fa-home mr-2"></i> হোম পেজে ফিরে যান
                </a>
                
                <a href="javascript:history.back()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 py-3 px-6 rounded-lg font-medium transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> পূর্ববর্তী পেজে ফিরে যান
                </a>
            </div>
        </div>
        
        <div class="mt-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                যদি আপনি মনে করেন যে এটি একটি সমস্যা, তাহলে <a href="{{ route('support.create') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">আমাদের সাথে যোগাযোগ করুন</a>।
            </p>
        </div>
    </div>
</div>
@endsection 
@include('partials.mobile-nav')