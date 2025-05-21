# OTP System Documentation

## Overview

The OTP (One-Time Password) system provides secure authentication capabilities for user verification, password resets, and other sensitive operations. This document outlines the architecture, configuration, and usage of the OTP system.

## Supported OTP Services

The system currently supports the following OTP services:

1. **Twilio SMS** - For sending OTP codes via SMS
2. **CallMeBot WhatsApp** - For sending OTP codes via WhatsApp

## System Architecture

### Core Components

1. **OtpConfiguration Model**: Manages service configurations stored in the database
2. **OtpService**: Handles OTP generation, validation, and delivery
3. **OtpController**: Processes API requests for OTP operations
4. **OTP Admin Panel**: Manages OTP service configurations

### Database Structure

**Table: otp_configurations**

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| service_name | VARCHAR(255) | Service identifier (e.g., 'twilio_sms', 'callmebot_whatsapp') |
| api_key | TEXT | Primary API credential (Account SID for Twilio) |
| api_secret | TEXT | Secondary API credential (Auth Token for Twilio) |
| additional_config | TEXT | JSON-encoded additional configuration (e.g., from_number) |
| is_enabled | BOOLEAN | Whether the service is active |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

## Installation and Setup

### Prerequisites

- PHP 7.4+ with Laravel
- Composer for package management
- Valid Twilio account (for SMS)
- Valid WhatsApp account (for CallMeBot)

### Basic Setup

1. **Install Twilio SDK**:
   ```
   composer require twilio/sdk
   ```

2. **Configure Services**:
   Run the comprehensive setup script:
   ```
   php setup_twilio_complete.php
   ```

   This script will:
   - Install necessary dependencies
   - Configure database entries
   - Set up environment variables

3. **Manual Configuration**:
   If needed, you can manually configure services through the admin panel or using the following scripts:
   - `update_twilio.php` - Update Twilio configuration
   - `enable_otp_testing.php` - Enable testing mode
   - `disable_otp_testing.php` - Disable testing mode

## Configuration Management

### Environment Variables

- `OTP_TESTING_MODE`: When set to `true`, OTPs will be logged instead of sent
- `OTP_CODE_LENGTH`: Controls the length of generated OTP codes (default: 6)
- `OTP_EXPIRY_MINUTES`: Controls how long OTPs remain valid (default: 15)

### Testing Mode

In testing mode, OTPs are not actually sent to phones but are logged in the application output. This is useful for development and testing.

To enable testing mode:
```
php enable_otp_testing.php
```

To disable testing mode (send real messages):
```
php disable_otp_testing.php
```

## Twilio SMS Configuration

### Required Credentials

1. **Account SID**: Your Twilio account identifier
2. **Auth Token**: Your Twilio authentication token
3. **From Number**: A valid Twilio phone number to send messages from

### Obtaining Credentials

1. Sign up at [Twilio](https://www.twilio.com/)
2. Navigate to your Dashboard to find Account SID and Auth Token
3. Purchase a phone number in the Twilio console
4. Update the configuration with these values

## OTP System API

### Generate OTP

```
POST /api/otp/generate
{
  "phone_number": "+1234567890",
  "service": "twilio_sms",  // Optional, defaults to first enabled service
  "purpose": "login"        // Optional, for tracking
}
```

### Verify OTP

```
POST /api/otp/verify
{
  "phone_number": "+1234567890",
  "otp_code": "123456",
  "purpose": "login"        // Should match the purpose used in generate
}
```

## Troubleshooting

### Common Issues

1. **OTPs not being sent**
   - Check if testing mode is enabled
   - Verify Twilio credentials are correct
   - Ensure the service is enabled in the database

2. **Invalid phone number format**
   - Phone numbers should be in E.164 format (e.g., +1234567890)

3. **Twilio errors**
   - Check Twilio dashboard for account balance
   - Verify phone number is verified (in trial accounts)
   - Check logs for specific error messages

### Checking Configuration

Run the configuration check script:
```
php check_otp_config.php
```

### Testing OTP Sending

Test sending an OTP:
```
php check_otp_config.php test your_mobile_number
```

## Development

### Adding a New OTP Service

1. Create a new configuration entry in the `otp_configurations` table
2. Extend the `OtpService` class to handle the new service
3. Update the admin panel to include configuration for the new service

### Security Considerations

- OTP codes should expire after a reasonable time (default: 15 minutes)
- Failed verification attempts should be limited to prevent brute force attacks
- Phone numbers should be validated before sending OTPs
- Consider implementing rate limiting for OTP generation

## Support and Resources

- [Twilio Documentation](https://www.twilio.com/docs)
- [CallMeBot API Documentation](https://www.callmebot.com/blog/free-api-whatsapp-messages/)
- Laravel Documentation for related components 