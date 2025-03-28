# Subshero Admin Guide

This guide provides detailed instructions for administrators on how to manage and configure the Subshero application.

## Table of Contents

- [Admin Dashboard Overview](#admin-dashboard-overview)
- [User Management](#user-management)
- [Product Management](#product-management)
- [Email Configuration](#email-configuration)
- [System Settings](#system-settings)
- [Update Management](#update-management)
- [Backup and Recovery](#backup-and-recovery)

## Admin Dashboard Overview

The admin dashboard provides a comprehensive overview of the Subshero application, including:

- **User Statistics**: Total number of users, active users, and new users
- **Subscription Statistics**: Total number of subscriptions, active subscriptions, and subscription value
- **System Status**: Current system status, including version information and update availability
- **Recent Activity**: Recent user actions and system events

To access the admin dashboard:

1. Log in with an admin account
2. Click on **Admin** in the main menu

## User Management

### Viewing Users

To view all users:

1. Click on **Admin** in the main menu
2. Click on **Users**
3. You'll see a list of all users in the system
4. Use the search and filter options to find specific users

### Creating a User

To create a new user:

1. Click on **Admin** in the main menu
2. Click on **Users**
3. Click the **Add User** button
4. Fill in the user details:
   - **Name**: User's name
   - **Email**: User's email address
   - **Password**: User's password
   - **Role**: Select the user's role (Admin or Client)
   - **Plan**: Select the user's plan
5. Click **Save** to create the user

### Editing a User

To edit a user:

1. Click on **Admin** in the main menu
2. Click on **Users**
3. Find the user you want to edit
4. Click the **Edit** button
5. Update the user details
6. Click **Save** to update the user

### Deleting a User

To delete a user:

1. Click on **Admin** in the main menu
2. Click on **Users**
3. Find the user you want to delete
4. Click the **Delete** button
5. Confirm the deletion

### Managing User Plans

To manage a user's plan:

1. Click on **Admin** in the main menu
2. Click on **Users**
3. Find the user you want to manage
4. Click the **Edit** button
5. Update the user's plan
6. Click **Save** to update the user

## Product Management

Subshero includes a product database that users can select from when creating subscriptions.

### Viewing Products

To view all products:

1. Click on **Admin** in the main menu
2. Click on **Products**
3. You'll see a list of all products in the system
4. Use the search and filter options to find specific products

### Adding a Product

To add a new product:

1. Click on **Admin** in the main menu
2. Click on **Products**
3. Click the **Add Product** button
4. Fill in the product details:
   - **Product Name**: Name of the product
   - **Brand Name**: Name of the brand
   - **Product Type**: Select the product type
   - **Description**: Brief description of the product
   - **URL**: Product website URL
   - **Image**: Upload a product image
   - **Favicon**: Upload a product favicon
   - **Pricing Type**: Select the pricing type
   - **Currency Code**: Select the currency
   - **Price**: Enter the price
   - **Refund Days**: Enter the refund period in days
   - **Billing Cycle**: Select the billing cycle
5. Click **Save** to add the product

### Editing a Product

To edit a product:

1. Click on **Admin** in the main menu
2. Click on **Products**
3. Find the product you want to edit
4. Click the **Edit** button
5. Update the product details
6. Click **Save** to update the product

### Deleting a Product

To delete a product:

1. Click on **Admin** in the main menu
2. Click on **Products**
3. Find the product you want to delete
4. Click the **Delete** button
5. Confirm the deletion

### Managing Product Categories

To manage product categories:

1. Click on **Admin** in the main menu
2. Click on **Products**
3. Click on the **Categories** tab
4. To add a category:
   - Click the **Add Category** button
   - Enter the category name
   - Click **Save** to add the category
5. To edit a category:
   - Click the **Edit** button next to the category
   - Update the category name
   - Click **Save** to update the category
6. To delete a category:
   - Click the **Delete** button next to the category
   - Confirm the deletion

### Managing Product Types

To manage product types:

1. Click on **Admin** in the main menu
2. Click on **Products**
3. Click on the **Types** tab
4. To add a type:
   - Click the **Add Type** button
   - Enter the type name
   - Click **Save** to add the type
5. To edit a type:
   - Click the **Edit** button next to the type
   - Update the type name
   - Click **Save** to update the type
6. To delete a type:
   - Click the **Delete** button next to the type
   - Confirm the deletion

### Importing Products

To import products:

1. Click on **Admin** in the main menu
2. Click on **Products**
3. Click on the **Import** tab
4. Click the **Download Template** button to get the import template
5. Fill in the template with your product data
6. Click the **Choose File** button and select your filled template
7. Click **Upload** to import the products
8. Review the import preview and make any necessary adjustments
9. Click **Import** to complete the import

### Exporting Products

To export products:

1. Click on **Admin** in the main menu
2. Click on **Products**
3. Click on the **Export** tab
4. Select the export format (CSV, Excel)
5. Click **Export** to download the product data

## Email Configuration

### SMTP Settings

To configure SMTP settings:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Email**
4. Click on the **SMTP** tab
5. Configure the SMTP settings:
   - **SMTP Host**: Your SMTP server host
   - **SMTP Port**: Your SMTP server port
   - **SMTP Encryption**: Select the encryption type (TLS, SSL)
   - **SMTP Username**: Your SMTP username
   - **SMTP Password**: Your SMTP password
   - **Sender Name**: The name that will appear in the From field
   - **Sender Email**: The email address that will appear in the From field
6. Click **Save** to update the SMTP settings
7. Click **Test** to send a test email

### Email Templates

To manage email templates:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Email**
4. Click on the **Templates** tab
5. You'll see a list of all email templates
6. To edit a template:
   - Click the **Edit** button next to the template
   - Update the template subject and body
   - Use the available placeholders to personalize the email
   - Click **Save** to update the template
7. To create a new template:
   - Click the **Add Template** button
   - Enter the template details
   - Click **Save** to create the template
8. To delete a template:
   - Click the **Delete** button next to the template
   - Confirm the deletion

### Email Logs

To view email logs:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Email**
4. Click on the **Logs** tab
5. You'll see a list of all emails sent by the system
6. Use the search and filter options to find specific emails
7. Click on an email to view its details
8. To delete all logs:
   - Click the **Delete All** button
   - Confirm the deletion

## System Settings

### General Settings

To configure general settings:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **General**
4. Configure the general settings:
   - **Application Name**: The name of the application
   - **Application URL**: The URL of the application
   - **Default Timezone**: The default timezone for the application
   - **Default Currency**: The default currency for the application
   - **Default Language**: The default language for the application
5. Click **Save** to update the general settings

### reCAPTCHA Settings

To configure reCAPTCHA settings:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Misc**
4. Configure the reCAPTCHA settings:
   - **reCAPTCHA Status**: Enable or disable reCAPTCHA
   - **reCAPTCHA Site Key**: Your reCAPTCHA site key
   - **reCAPTCHA Secret Key**: Your reCAPTCHA secret key
5. Click **Save** to update the reCAPTCHA settings

### CDN Settings

To configure CDN settings:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Misc**
4. Configure the CDN settings:
   - **CDN Status**: Enable or disable CDN
   - **CDN Base URL**: Your CDN base URL
5. Click **Save** to update the CDN settings

### Toggle Settings

To configure toggle settings:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Misc**
4. Configure the toggle settings:
   - **Login**: Enable or disable user login
   - **Registration**: Enable or disable user registration
   - **Password Reset**: Enable or disable password reset
5. Click **Save** to update the toggle settings

### Webhook Settings

To configure webhook settings:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Webhook**
4. Configure the webhook settings:
   - **Webhook Key**: Your webhook key
   - **Webhook URL**: Your webhook URL
   - **Webhook Events**: Select the events to trigger webhooks
5. Click **Save** to update the webhook settings
6. To view webhook logs:
   - Click on the **Logs** tab
   - You'll see a list of all webhook events
   - Click on an event to view its details

### Script Settings

To configure script settings:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Script**
4. Configure the script settings:
   - **Header Script**: Script to include in the header
   - **Footer Script**: Script to include in the footer
5. Click **Save** to update the script settings

## Update Management

Subshero includes a built-in update system that allows administrators to update the application to the latest version.

### Checking for Updates

To check for updates:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Update**
4. Click the **Check for Updates** button
5. If updates are available, you'll see information about the new version

### Updating the Application

To update the application:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Update**
4. Click the **Update Now** button
5. The system will guide you through the update process
6. Once the update is complete, you'll see a success message

For more detailed information about the update process, please refer to the [Update System Guide](update-system.md).

## Backup and Recovery

### Creating a Backup

To create a backup:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Backup**
4. Click the **Create Backup** button
5. The system will create a backup of the database and files
6. Once the backup is complete, you'll see it in the list of backups

### Restoring a Backup

To restore a backup:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Backup**
4. Find the backup you want to restore
5. Click the **Restore** button
6. Confirm the restoration
7. The system will restore the database and files from the backup
8. Once the restoration is complete, you'll see a success message

### Downloading a Backup

To download a backup:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Backup**
4. Find the backup you want to download
5. Click the **Download** button
6. The backup will be downloaded to your computer

### Deleting a Backup

To delete a backup:

1. Click on **Admin** in the main menu
2. Click on **Settings**
3. Click on **Backup**
4. Find the backup you want to delete
5. Click the **Delete** button
6. Confirm the deletion

## Conclusion

This guide covers the main administrative features of Subshero. For more detailed information or specific use cases, please refer to the other documentation sections or contact the Subshero support team.