# Plan for Serving the Laravel Project

## Overview
This document outlines the steps and options for serving the Subshero Laravel project.

## Prerequisites
- PHP >= 8.0.2
- Composer installed
- Project dependencies installed (`composer install`)
- NPM dependencies installed (`npm install`)
- Configured `.env` file
- Database set up and migrations run

## Serving Options

### Option 1: Using Laravel's Built-in Development Server
This is the simplest way to serve a Laravel application for development purposes:

```bash
php artisan serve
```

This will start a development server at http://127.0.0.1:8000

You can also specify a host and port if needed:

```bash
php artisan serve --host=0.0.0.0 --port=8080
```

### Option 2: Using Laragon's Built-in Server
Since you're using Laragon (based on your project path), you can also use Laragon's built-in server:

1. Make sure Laragon is running
2. Access your project at http://subshero.test (Laragon automatically creates a .test domain for each project in its www directory)

### Option 3: Using a Web Server (Apache/Nginx)

#### For Apache (already configured in Laragon):
- Ensure your virtual host is configured to point to the `/public` directory
- Access your site at the configured domain

#### For Nginx (if configured in Laragon):
- Ensure your server block is configured to point to the `/public` directory
- Access your site at the configured domain

## Recommended Approach
Since you're using Laragon, the simplest approach would be:

1. Start Laragon (if not already running)
2. Access your project at http://subshero.test

Alternatively, if you prefer to use Laravel's built-in server:

```bash
php artisan serve
```

## Additional Configuration

### Setting Up a Cron Job for Scheduled Tasks
If your application uses Laravel's scheduler, you'll need to set up a cron job:

```bash
* * * * * cd /path/to/subshero && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Worker (if needed)
If your application uses queues, you may need to start a queue worker:

```bash
php artisan queue:work
```

## Troubleshooting
If you encounter issues while serving the project, refer to the [Troubleshooting Guide](docs/troubleshooting.md) or check Laravel's official documentation.