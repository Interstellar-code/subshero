# Subshero Troubleshooting Guide

This guide provides solutions to common issues you might encounter while using Subshero.

## Table of Contents

- [Installation Issues](#installation-issues)
- [Login Issues](#login-issues)
- [Subscription Management Issues](#subscription-management-issues)
- [Email Notification Issues](#email-notification-issues)
- [Performance Issues](#performance-issues)
- [Update Issues](#update-issues)
- [API Issues](#api-issues)
- [Common Error Messages](#common-error-messages)

## Installation Issues

### Database Connection Error

**Issue**: You receive a database connection error during installation.

**Solution**:
1. Verify that your database server is running
2. Check that the database credentials in your `.env` file are correct
3. Ensure the database exists and the user has appropriate permissions
4. Try connecting to the database using a different tool to verify the credentials

### Permission Denied Errors

**Issue**: You receive "Permission denied" errors during installation.

**Solution**:
1. Ensure the web server has write permissions to the following directories:
   - `storage/`
   - `bootstrap/cache/`
   - `public/`
2. Run the following commands:
   ```bash
   chmod -R 775 storage bootstrap/cache public
   chown -R www-data:www-data storage bootstrap/cache public
   ```
   (Replace `www-data` with your web server user)

### Composer Dependency Issues

**Issue**: Composer fails to install dependencies.

**Solution**:
1. Ensure you have the required PHP extensions installed
2. Try clearing Composer's cache:
   ```bash
   composer clear-cache
   ```
3. Update Composer:
   ```bash
   composer self-update
   ```
4. Try installing dependencies with the `--no-dev` flag:
   ```bash
   composer install --no-dev
   ```

### 500 Internal Server Error After Installation

**Issue**: You receive a 500 Internal Server Error after installation.

**Solution**:
1. Check your web server error logs for specific error messages
2. Ensure the `.env` file exists and is properly configured
3. Generate a new application key:
   ```bash
   php artisan key:generate
   ```
4. Clear the application cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

## Login Issues

### Can't Log In with Correct Credentials

**Issue**: You can't log in even though you're using the correct credentials.

**Solution**:
1. Clear your browser cookies and cache
2. Try using a different browser
3. Reset your password using the "Forgot Password" link
4. Check if your account is locked due to too many failed login attempts
5. Verify that your account exists and is active in the database

### Session Expires Too Quickly

**Issue**: Your session expires too quickly, requiring frequent logins.

**Solution**:
1. Check the session timeout setting in `config/session.php`
2. Increase the `lifetime` value in `config/session.php`
3. Ensure your browser is accepting cookies
4. Check if you have any browser extensions that might be clearing cookies

### Two-Factor Authentication Issues

**Issue**: You're having trouble with two-factor authentication.

**Solution**:
1. Ensure your device's time is synchronized correctly
2. Try using the recovery codes provided when you set up 2FA
3. Contact an administrator to disable 2FA for your account

## Subscription Management Issues

### Can't Create a Subscription

**Issue**: You receive an error when trying to create a subscription.

**Solution**:
1. Check if you've reached your plan's subscription limit
2. Ensure all required fields are filled out correctly
3. Verify that the product you're trying to subscribe to exists
4. Check if you have the necessary permissions to create subscriptions

### Subscription Not Showing Up

**Issue**: A newly created subscription doesn't appear in your list.

**Solution**:
1. Refresh the page
2. Clear your browser cache
3. Check if the subscription was saved with a different status (e.g., draft)
4. Verify that you're looking in the correct folder or view

### Can't Edit a Subscription

**Issue**: You can't edit a subscription or changes aren't saving.

**Solution**:
1. Check if you have the necessary permissions to edit the subscription
2. Ensure all required fields are filled out correctly
3. Try using a different browser
4. Clear your browser cache and cookies

### Incorrect Renewal Dates

**Issue**: Subscription renewal dates are incorrect.

**Solution**:
1. Check your timezone settings in your profile
2. Verify that the billing cycle and frequency are set correctly
3. Edit the subscription to correct the payment date and renewal date
4. Check if the subscription has been renewed but not updated in the UI

## Email Notification Issues

### Not Receiving Email Notifications

**Issue**: You're not receiving email notifications for alerts or renewals.

**Solution**:
1. Check your spam or junk folder
2. Verify that your email address is correct in your profile
3. Ensure that email notifications are enabled in your alert preferences
4. Check if the SMTP settings are configured correctly (admin only)
5. Test the email configuration (admin only)

### Email Notifications Delayed

**Issue**: Email notifications are significantly delayed.

**Solution**:
1. Check if the queue worker is running (admin only):
   ```bash
   php artisan queue:work
   ```
2. Verify that the cron job is set up correctly (admin only)
3. Check the email server for delays or issues (admin only)

### Incorrect Information in Email Notifications

**Issue**: Email notifications contain incorrect information.

**Solution**:
1. Update your subscription details to correct the information
2. Check if the email template has been customized incorrectly (admin only)
3. Verify that the system is using the correct email template (admin only)

## Performance Issues

### Slow Loading Times

**Issue**: Pages take a long time to load.

**Solution**:
1. Clear your browser cache
2. Check your internet connection
3. Optimize the database (admin only):
   ```bash
   php artisan optimize
   ```
4. Enable caching (admin only):
   ```bash
   php artisan config:cache
   php artisan route:cache
   ```
5. Consider using a CDN for static assets (admin only)

### High Memory Usage

**Issue**: The application uses a lot of memory, causing slowdowns or crashes.

**Solution**:
1. Increase the PHP memory limit in `php.ini` (admin only)
2. Optimize database queries (admin only)
3. Implement pagination for large data sets (admin only)
4. Consider upgrading your server resources (admin only)

### Database Connection Issues

**Issue**: You experience intermittent database connection issues.

**Solution**:
1. Check if the database server is overloaded (admin only)
2. Increase the maximum connections in your database configuration (admin only)
3. Optimize database queries (admin only)
4. Consider implementing a connection pooling solution (admin only)

## Update Issues

### Update Process Fails

**Issue**: The update process fails or gets stuck.

**Solution**:
1. Check the server requirements for the new version
2. Ensure you have enough disk space
3. Verify that all directories are writable by the web server
4. Try updating manually (see [Update System Guide](update-system.md))
5. Check the error logs for specific error messages

### Database Migration Errors

**Issue**: You receive database migration errors during the update.

**Solution**:
1. Backup your database before attempting again
2. Check if the database user has the necessary permissions
3. Try running the migrations manually:
   ```bash
   php artisan migrate
   ```
4. Check the error logs for specific error messages

### Application Error After Update

**Issue**: The application shows errors or doesn't work correctly after an update.

**Solution**:
1. Clear all caches:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   php artisan route:clear
   ```
2. Regenerate the autoload files:
   ```bash
   composer dump-autoload
   ```
3. Check if all dependencies are installed:
   ```bash
   composer install
   ```
4. Verify that the `.env` file is correctly configured
5. Check the error logs for specific error messages

## API Issues

### Authentication Errors

**Issue**: You receive authentication errors when using the API.

**Solution**:
1. Verify that your API token is correct and not expired
2. Ensure you're including the token in the correct format:
   ```
   Authorization: Bearer your-api-token
   ```
3. Check if your account has API access permissions
4. Generate a new API token if necessary

### Rate Limiting

**Issue**: You're hitting the API rate limit.

**Solution**:
1. Reduce the frequency of your API requests
2. Implement caching on your side to reduce the need for repeated requests
3. Contact the administrator to request a higher rate limit

### Incorrect Response Format

**Issue**: The API returns responses in an unexpected format.

**Solution**:
1. Ensure you're specifying the correct `Accept` header:
   ```
   Accept: application/json
   ```
2. Check if you're using the correct API version
3. Verify that your request is properly formatted
4. Check the API documentation for the expected response format

## Common Error Messages

### "Whoops, something went wrong."

**Issue**: You see a generic error page with "Whoops, something went wrong."

**Solution**:
1. Check the Laravel log file in `storage/logs/laravel.log` for detailed error information
2. Enable debugging in `.env` by setting `APP_DEBUG=true` (temporarily, for development only)
3. Clear application caches:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

### "419 Page Expired"

**Issue**: You receive a "419 Page Expired" error when submitting a form.

**Solution**:
1. This is usually caused by an expired CSRF token
2. Refresh the page and try again
3. Ensure your session is configured correctly
4. Check if you're using the `@csrf` directive in your forms

### "500 Internal Server Error"

**Issue**: You receive a "500 Internal Server Error".

**Solution**:
1. Check the web server error logs for detailed information
2. Check the Laravel log file in `storage/logs/laravel.log`
3. Enable debugging in `.env` by setting `APP_DEBUG=true` (temporarily, for development only)
4. Verify that all required PHP extensions are installed
5. Check if there are any syntax errors in your custom code

### "404 Not Found"

**Issue**: You receive a "404 Not Found" error for a page that should exist.

**Solution**:
1. Verify that the URL is correct
2. Check if the route is defined in `routes/web.php` or `routes/api.php`
3. Clear the route cache:
   ```bash
   php artisan route:clear
   ```
4. Check if the controller and method exist and are correctly named

## Still Need Help?

If you're still experiencing issues after trying these solutions, please contact the Subshero support team with the following information:

1. Detailed description of the issue
2. Steps to reproduce the issue
3. Error messages or screenshots
4. Your Subshero version
5. Your server environment (PHP version, database version, etc.)
6. Any relevant log entries from `storage/logs/laravel.log`

This information will help the support team diagnose and resolve your issue more quickly.