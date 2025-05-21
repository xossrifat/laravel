# OTP Services Setup Guide

This guide provides step-by-step instructions for setting up OTP (One-Time Password) services for your Spin & Earn application.

## Available OTP Services

The application supports two OTP services:
1. **Twilio SMS** - For sending OTPs via SMS
2. **CallMeBot WhatsApp** - For sending OTPs via WhatsApp

## Setup Instructions

### Twilio SMS Setup

Twilio SMS requires an account with Twilio and a phone number that can send SMS.

1. **Create a Twilio Account**:
   - Go to [Twilio's website](https://www.twilio.com/try-twilio)
   - Sign up for a new account
   - Verify your email and phone number

2. **Get Your Account Credentials**:
   - In the Twilio Console, locate your Account SID and Auth Token
   - These credentials will be used to authenticate API requests

3. **Purchase a Phone Number**:
   - In the Twilio Console, go to Phone Numbers > Buy a Number
   - Make sure the number has SMS capabilities
   - Purchase a number that will be used to send OTP messages

4. **Update OTP Configuration**:
   - In your admin panel, go to OTP Configuration
   - Enable Twilio SMS
   - Enter your Account SID, Auth Token, and From Phone Number
   - The From Phone Number should include the country code (e.g., +1XXXXXXXXXX)

5. **Troubleshooting Twilio SMS**:
   - Check Twilio console logs for errors
   - Verify that your account has sufficient credit
   - Ensure the phone number has SMS capabilities
   - Confirm that the recipient phone number is in the correct format
   - Check if there are any geographic restrictions for your SMS sending

### CallMeBot WhatsApp Setup

CallMeBot is a free service for sending WhatsApp messages:

1. **Register Your Phone**:
   - Send the message "I allow callmebot to send me messages" to the CallMeBot WhatsApp number: +34 644 68 16 77
   - Wait for the confirmation message with your personal API key

2. **Get Your API Key**:
   - After registration, you'll receive a personal API key
   - This key is linked to your WhatsApp number

3. **Update OTP Configuration**:
   - In your admin panel, go to OTP Configuration
   - Enable CallMeBot WhatsApp
   - Enter your personal API key
   - Verify the API URL is set to https://api.callmebot.com/whatsapp.php

4. **Test Your Setup**:
   - Use the test function in the OTP Configuration panel
   - Enter a test number (same as the one you registered with CallMeBot)
   - Click "Test WhatsApp OTP"

## Testing OTP Services

### Testing in Development

In development environments, you can enable OTP Testing Mode:

1. Add to your `.env` file:
   ```
   OTP_TESTING_MODE=true
   ```

2. When testing mode is enabled:
   - OTPs will be logged instead of actually sent
   - Check `storage/logs/otp.log` for the OTP codes
   - Use the debug routes (in non-production environments):
     - `/debug/otp` - OTP testing dashboard
     - `/debug/otp/sms` - Test SMS OTP
     - `/debug/otp/whatsapp` - Test WhatsApp OTP
     - `/debug/otp/view` - View cached OTPs

### Command Line Testing

You can also test OTP sending from the command line:

```bash
# Test both SMS and WhatsApp OTP sending
php test_send_sms.php +1234567890

# Check OTP configuration status
php check_otp_config.php

# Test sending OTP via script
php check_otp_config.php test +1234567890
```

## Production Considerations

When deploying to production:

1. Ensure `OTP_TESTING_MODE` is set to `false` or removed
2. Verify that your Twilio account has sufficient credit for SMS sending
3. Test sending actual OTPs before going live
4. Consider implementing rate limiting for OTP requests
5. Monitor your logs for any SMS sending failures
6. Have a fallback mechanism (like WhatsApp) if SMS fails

## Switching Between Services

You can enable/disable services as needed:

- If SMS is not working, you can temporarily switch to WhatsApp
- Update the default channel in your controller as needed

## Logging and Debugging

OTP-related logs are stored in:
- `storage/logs/otp.log`

These logs include:
- OTP generation events
- Sending attempts (success/failure)
- Verification attempts
- Error details

## Support and Resources

- **Twilio Documentation**: [Twilio SMS API](https://www.twilio.com/docs/sms)
- **CallMeBot Documentation**: [CallMeBot WhatsApp API](https://www.callmebot.com/blog/free-api-whatsapp-messages/)
- **Internal Documentation**: See additional docs in the `/docs` directory 