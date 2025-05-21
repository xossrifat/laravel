@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-4">Profile Settings</h2>

    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>

    <!-- এখান থেকে তুমি ফর্ম দিয়ে নাম, ইমেইল, পাসওয়ার্ড চেঞ্জ করার অপশন দিতে পারো -->
</div>
@endsection
@include('partials.mobile-nav')