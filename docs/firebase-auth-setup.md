# Firebase Authentication Setup

This document explains how to set up Firebase Authentication for phone number login and registration in the Spin Earn App.

## Prerequisites

1. A Firebase project - Create one at [Firebase Console](https://console.firebase.google.com/)
2. Enable Phone Authentication in your Firebase project

## Setup Instructions

### 1. Create a Firebase Project

1. Go to the [Firebase Console](https://console.firebase.google.com/)
2. Click "Add project" and follow the setup wizard
3. Give your project a name and accept the terms
4. Optionally enable Google Analytics
5. Click "Create project"

### 2. Add a Web App to Your Firebase Project

1. In your Firebase project dashboard, click the web icon (</>) to add a web app
2. Register your app with a nickname (e.g., "Spin Earn Web")
3. Optionally set up Firebase Hosting
4. Click "Register app"
5. Copy the Firebase configuration (you'll need this later)

### 3. Enable Phone Authentication

1. In the Firebase Console, go to "Authentication" in the left sidebar
2. Click the "Sign-in method" tab
3. Click "Phone" in the list of providers
4. Toggle the "Enable" switch to on
5. Click "Save"

### 4. Configure the App

Run the Firebase migration script:

```bash
php artisan migrate --path=database/migrations/2025_06_01_000000_add_firebase_auth_configuration.php
```

Or run the helper script:

```bash
php run_firebase_migration.php
```

### 5. Update Firebase Configuration

1. Run the setup script:

```bash
php setup_firebase_auth.php
```

2. Edit the script to include your actual Firebase configuration:

```php
// Set default configuration values
// Replace these with your actual Firebase configuration
$firebaseConfig->api_key = 'YOUR_FIREBASE_API_KEY';
$firebaseConfig->is_enabled = true;

// Store Firebase project configuration
$additionalConfig = [
    'project_id' => 'YOUR_FIREBASE_PROJECT_ID',
    'app_id' => 'YOUR_FIREBASE_APP_ID',
    'auth_domain' => 'YOUR_FIREBASE_PROJECT_ID.firebaseapp.com',
    'messaging_sender_id' => 'YOUR_FIREBASE_SENDER_ID',
    'recaptcha_site_key' => '...',  // Optional: For invisible reCAPTCHA
    'use_emulator' => false,  // Set to true for local development with Firebase emulator
];
```

3. Run the script again:

```bash
php setup_firebase_auth.php
```

### 6. Test the Configuration

Run the test script to verify your Firebase configuration:

```bash
php test_firebase_auth.php
```

### 7. Admin Configuration

You can also configure Firebase Authentication in the admin panel:

1. Go to Admin > OTP Configuration
2. Enter your Firebase configuration details
3. Click "Save Configuration"
4. Click "Test Configuration" to verify

## Usage

### Login with Phone Number

Users can now log in with their phone number using Firebase Authentication. The process is:

1. User enters their phone number
2. Firebase sends an SMS with a verification code
3. User enters the verification code
4. Firebase verifies the code and authenticates the user
5. User is logged in to the app

### Register with Phone Number

New users can register with their phone number:

1. User enters their phone number
2. Firebase sends an SMS with a verification code
3. User enters the verification code
4. Firebase verifies the code
5. User enters additional registration information
6. A new account is created and the user is logged in

## Troubleshooting

If you encounter issues with Firebase Authentication:

1. Check that your Firebase project is properly set up
2. Verify that Phone Authentication is enabled in Firebase
3. Ensure your Firebase configuration is correct in the app
4. Check the Firebase console for any error messages
5. Run the test script to verify your configuration 