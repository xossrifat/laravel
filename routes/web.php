<?php

use Illuminate\Support\Facades\Route;

// Main application is moved to a separate project
// Only admin panel routes remain in this application
Route::get('/', function() {
    return redirect()->route('admin.login');
});

// Include admin routes
require __DIR__.'/admin.php';
