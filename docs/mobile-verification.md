# Mobile Verification System

The Spin & Earn app includes a comprehensive mobile verification system to ensure user authenticity and add an extra layer of security for sensitive operations.

## Overview

The mobile verification system allows users to:
- Register and link a mobile number to their account
- Verify ownership of the mobile number via OTP (One-Time Password)
- Choose their preferred OTP channel (SMS or WhatsApp)
- Update their mobile number when needed

Certain actions within the app require a verified mobile number, such as:
- Withdrawing funds to payment methods
- Updating payment settings
- Other security-sensitive operations

## Components

The mobile verification system consists of several components:

### User-Facing Components

1. **Verification Form**: Allows users to enter their mobile number and select preferred OTP channel
2. **OTP Verification**: Allows users to enter the OTP sent to their mobile number
3. **Settings Integration**: Shows mobile verification status in user settings

### Admin Components

1. **OTP Configuration**: Admin dashboard for configuring OTP service providers
2. **User Management**: Admin tools to view and manage user mobile verification status

### Backend Components

1. **OtpService**: Service class for generating, sending, and verifying OTPs
2. **OtpConfiguration Model**: Database model for storing OTP service configurations
3. **MobileVerificationController**: Controller for handling mobile verification requests
4. **Middlewares**: For enforcing mobile verification on protected routes

## Workflow

### Mobile Number Registration

1. User navigates to the mobile verification section
2. User enters their mobile number and selects preferred OTP channel
3. System sends OTP to the provided number via chosen channel
4. User enters OTP to verify ownership of the number
5. System marks the mobile number as verified if OTP is correct

### Protected Actions

1. User attempts to access a protected feature (e.g., withdrawal)
2. System checks if the user has a verified mobile number
3. If verified, user proceeds to the feature
4. If not verified, user is redirected to the verification page

## OTP Service Providers

The system supports multiple OTP service providers:

### Firebase SMS

Uses Firebase Authentication for sending SMS OTPs. Configuration includes:
- API Key
- Project ID
- Service Account JSON

### CallMeBot WhatsApp

Uses CallMeBot API for sending WhatsApp OTPs. Configuration includes:
- Auth Token
- API URL

## Security Considerations

- OTPs expire after 10 minutes
- OTP validation is rate-limited to prevent brute force attacks
- Mobile numbers are uniquely assigned to users (no duplicates)
- Sensitive actions require mobile verification

## Admin Configuration

Administrators can configure the OTP services through the admin panel:
1. Navigate to Admin > Mobile Verification
2. Configure SMS and WhatsApp providers
3. Test the configurations with real mobile numbers
4. Enable or disable specific providers

## Command Line Tools

The system includes command-line tools for administration:

```bash
# Check mobile verification status of users
php artisan users:check-mobile-verification

# Check only unverified users
php artisan users:check-mobile-verification --unverified

# Check only verified users
php artisan users:check-mobile-verification --verified

# Test OTP services
php test_otp_services.php test-sms
php test_otp_services.php test-whatsapp
```

## Development & Testing

During development and testing, the OTP service can be configured to log OTPs instead of actually sending them. This allows developers to test the verification flow without needing actual mobile numbers or integration with third-party services.

To enable this mode, set the `OTP_TESTING_MODE=true` in the `.env` file. 