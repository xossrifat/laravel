# Spin-Earn Admin Panel

This project contains only the admin panel of the Spin-Earn application. The main user-facing application has been moved to a separate project.

## Admin Panel Features

1. **User Management**
   - View and manage users
   - Ban/unban users
   - Modify user coin balances
   - View user spin and video watch history

2. **Spin Configuration**
   - Configure spin rewards
   - Set reward probabilities
   - Update spin limits
   - Configure spin ads

3. **Watch & Earn Control**
   - Configure video rewards
   - Manage video ads
   - Set limits and rewards

4. **Transaction & Withdrawal Management**
   - View withdrawal requests
   - Approve/reject withdrawals
   - Configure withdrawal settings

5. **Analytics**
   - User activity analytics
   - Spin and video watch statistics
   - Revenue analytics
   - Withdrawal analytics

6. **Settings**
   - General application settings
   - Appearance settings
   - Maintenance mode
   - Security settings

7. **Shortlinks Management**
   - Create and manage shortlinks
   - Configure shortlink rewards
   - View shortlink analytics

8. **Telegram Integration**
   - Configure Telegram bot
   - Send messages to users
   - Manage Telegram users

9. **Support Management**
   - View and respond to support tickets
   - Close/reopen support tickets

10. **Email Management**
    - Configure email templates
    - Email settings
    - Send test emails

## Technical Information

This admin panel has been separated from the main application to:
1. Improve security by isolating admin functionality
2. Allow independent deployment and scaling
3. Simplify maintenance and updates

### Routes

The admin routes are defined in `routes/admin.php` and included in `routes/web.php`. The default route redirects to the admin login page.

### Authentication

Admin authentication is handled separately from user authentication using a dedicated Admin model and guard.

### Database

This application shares the same database schema as the main application, but only focuses on administration-related functionality.

## Setup & Installation

1. Clone this repository
2. Install dependencies: `composer install`
3. Set up your `.env` file with database credentials
4. Run migrations: `php artisan migrate`
5. Start the server: `php artisan serve`

**Note:** This is only the admin panel. The main user-facing application should be set up as a separate project. 