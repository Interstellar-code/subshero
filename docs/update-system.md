# Subshero Update System Guide

Subshero includes a built-in update system that allows administrators to easily update the application to the latest version. This guide explains how the update system works and how to use it.

## Overview

The update system in Subshero provides a seamless way to:

1. Check for new versions
2. Download update packages
3. Apply updates to the application
4. Migrate the database schema
5. Execute catalog updates

The entire process is managed through the admin panel and requires minimal technical knowledge.

## How the Update System Works

The update system follows these steps:

1. **Version Check**: The system checks for available updates by comparing the current version with the latest version available on the update server.
2. **Update Start**: If a new version is available, the update process begins.
3. **Maintenance Mode**: The system enables maintenance mode to prevent users from accessing the application during the update.
4. **Download**: The system downloads the update package files and database update scripts.
5. **Extract Archive**: The downloaded packages are extracted.
6. **SQL Execute**: Database update scripts are executed to update the database schema.
7. **Files Replace**: Application files are replaced with the new versions.
8. **Catalog Check**: The system checks for catalog updates.
9. **Catalog Execute**: If catalog updates are available, they are executed.
10. **Maintenance Disable**: Maintenance mode is disabled, allowing users to access the application again.
11. **Update Success**: The update is complete, and the application is now running the latest version.

## Accessing the Update System

To access the update system:

1. Log in to the admin panel
2. Navigate to **Settings > Update**

## Checking for Updates

To check for updates:

1. Go to the Update page in the admin panel
2. Click the "Check for Updates" button
3. If updates are available, you'll see information about the new version

## Updating the Application

To update the application:

1. After checking for updates and confirming a new version is available, click the "Update Now" button
2. The system will guide you through the update process
3. You'll see progress indicators for each step of the update
4. Once the update is complete, you'll see a success message

## Update Process Details

### Version Check

The system compares your current version with the latest version available on the update server. It handles both regular releases and beta versions.

### Maintenance Mode

During the update, the system enables maintenance mode to prevent users from accessing the application. This ensures that no data is modified during the update process.

```php
Storage::disk('local')->put('maintenance.txt', Carbon::now()->toDateTimeString());
```

### File Updates

The update system downloads and extracts two packages:

1. **Database_package.zip**: Contains database update scripts
2. **Update_package.zip**: Contains updated application files

### Database Updates

The system executes SQL scripts to update the database schema. This ensures that the database structure is compatible with the new version of the application.

### Catalog Updates

Catalog updates are additional updates that may be required after the main update process. These are executed if available.

### Cache Clearing

After updating the files, the system clears various caches to ensure the application runs smoothly:

```php
$code = Artisan::call('cache:clear');
$code = Artisan::call('view:clear');
$code = Artisan::call('config:cache');
```

## Manual Updates

If the automatic update system fails, you can perform a manual update:

1. Download the latest version from the official website
2. Back up your current installation and database
3. Replace the application files with the new ones
4. Run database migrations:
   ```bash
   php artisan migrate
   ```
5. Clear caches:
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan config:cache
   ```

## Troubleshooting

### Update Fails at Download Step

If the update fails during the download step:

1. Check your server's internet connection
2. Ensure your server has enough disk space
3. Verify that the `modules` directory is writable

### Update Fails at Database Update Step

If the update fails during the database update step:

1. Check the database connection settings
2. Ensure the database user has sufficient privileges
3. Look for error messages in the Laravel log file

### Update Fails at File Replacement Step

If the update fails during the file replacement step:

1. Ensure all application directories are writable
2. Check for file permission issues
3. Verify that no files are locked by the operating system

### Application Error After Update

If you encounter errors after updating:

1. Clear all caches:
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan config:cache
   ```
2. Check the Laravel log file for specific error messages
3. Verify that all database migrations were applied successfully

## Best Practices

1. **Always back up your application and database before updating**
2. Update during low-traffic periods to minimize disruption
3. Test the update on a staging environment before applying it to production
4. Review the changelog for each update to understand what changes are being made
5. After updating, test key functionality to ensure everything works as expected

## Version History

The system maintains a record of the application version in the `versions` table in the database. This allows the system to track which updates have been applied and which are still pending.

For more information about specific versions and their changes, refer to the official changelog.