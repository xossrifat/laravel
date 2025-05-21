<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\EmailSettingController;

// Admin email routes - these should be included in the admin.php routes
Route::middleware(['auth:admin'])->group(function () {
    // Email templates
    Route::get('email-templates', [EmailTemplateController::class, 'index'])->name('email_templates.index');
    Route::get('email-templates/create', [EmailTemplateController::class, 'create'])->name('email_templates.create');
    Route::post('email-templates', [EmailTemplateController::class, 'store'])->name('email_templates.store');
    Route::get('email-templates/{id}/edit', [EmailTemplateController::class, 'edit'])->name('email_templates.edit');
    Route::put('email-templates/{id}', [EmailTemplateController::class, 'update'])->name('email_templates.update');
    Route::delete('email-templates/{id}', [EmailTemplateController::class, 'destroy'])->name('email_templates.destroy');
    Route::get('email-templates/{id}/preview', [EmailTemplateController::class, 'preview'])->name('email_templates.preview');
    
    // Email settings
    Route::get('email-settings', [EmailSettingController::class, 'index'])->name('email_settings.index');
    Route::post('email-settings', [EmailSettingController::class, 'update'])->name('email_settings.update');
    Route::post('email-settings/test', [EmailSettingController::class, 'testEmail'])->name('email_settings.test');
}); 