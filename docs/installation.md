# Subshero Installation Guide

This guide will walk you through the process of installing Subshero on your server.

## System Requirements

Before installing Subshero, ensure your server meets the following requirements:

- PHP >= 8.0.2
- MySQL >= 5.7 or MariaDB >= 10.3
- Composer
- Node.js and NPM
- The following PHP extensions:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - ZIP

## Installation Steps

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/subshero.git
cd subshero
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Create Environment File

Copy the example environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure Environment Variables

Open the `.env` file and update the following settings:

```
APP_NAME=Subshero
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=subshero
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=your_mail_port
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 6. Create Database

Create a new MySQL database for Subshero:

```sql
CREATE DATABASE subshero CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Run Database Migrations

```bash
php artisan migrate
```

### 8. Import Initial Data

Import the initial database structure and data:

```bash
mysql -u your_database_username -p subshero < database/database.sql
```

### 9. Compile Assets

```bash
npm run prod
```

### 10. Set Directory Permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 11. Configure Web Server

#### Apache

Create a new virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/subshero/public

    <Directory /path/to/subshero/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/subshero-error.log
    CustomLog ${APACHE_LOG_DIR}/subshero-access.log combined
</VirtualHost>
```

Enable the site and restart Apache:

```bash
a2ensite subshero.conf
systemctl restart apache2
```

#### Nginx

Create a new server block configuration:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/subshero/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site and restart Nginx:

```bash
ln -s /etc/nginx/sites-available/subshero.conf /etc/nginx/sites-enabled/
systemctl restart nginx
```

### 12. Set Up Cron Job

Add the following cron job to run the Laravel scheduler:

```bash
* * * * * cd /path/to/subshero && php artisan schedule:run >> /dev/null 2>&1
```

### 13. Access the Application

Open your browser and navigate to your domain. You should see the Subshero login page.

Default login credentials:
- Email: admin@subshero.com
- Password: password

**Important:** Change the default password immediately after your first login.

## Upgrading

For information on how to upgrade Subshero to a newer version, please refer to the [Update System Guide](update-system.md).

## Troubleshooting

If you encounter any issues during installation, please check the [Troubleshooting Guide](troubleshooting.md) or create an issue on the GitHub repository.