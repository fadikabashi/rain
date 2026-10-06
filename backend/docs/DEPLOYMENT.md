# Deployment Guide

## Pre-Deployment Checklist

- [ ] All tests passing
- [ ] Code reviewed and approved
- [ ] Environment variables configured
- [ ] Database migrations ready
- [ ] Assets built for production
- [ ] SSL certificate configured
- [ ] Backup strategy in place

## Server Requirements

### Minimum Requirements

- PHP >= 8.2
- MySQL >= 5.7 or MariaDB >= 10.3
- Composer
- Node.js >= 16.x
- NPM >= 8.x

### Recommended Requirements

- PHP >= 8.2 with OPcache enabled
- MySQL >= 8.0 or MariaDB >= 10.6
- Redis (for caching and sessions)
- Nginx or Apache with mod_rewrite

## Deployment Steps

### Step 1: Prepare Server

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml \
  php8.2-curl php8.2-zip php8.2-gd composer nginx mysql-server
```

### Step 2: Clone Repository

```bash
cd /var/www
git clone <repository-url> ecommerce
cd ecommerce
```

### Step 3: Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install

# Build production assets
npm run build
```

### Step 4: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file
nano .env
```

**Production .env Configuration**:

```env
APP_NAME="E-Commerce"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error

# Performance Monitoring (optional)
ENABLE_PERFORMANCE_LOGGING=false
ENABLE_SLOW_QUERY_LOGGING=false
```

### Step 5: Database Setup

```bash
# Run migrations
php artisan migrate --force

# (Optional) Seed database
php artisan db:seed --force
```

### Step 6: Storage Setup

```bash
# Create storage link
php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Step 7: Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### Step 8: Configure Web Server

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/ecommerce/public;

    add_header X-Frame-Options "SAMEORIGIN";
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
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache Configuration

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/ecommerce/public

    <Directory /var/www/ecommerce/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

### Step 9: Set Up SSL (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d your-domain.com

# Auto-renewal
sudo certbot renew --dry-run
```

### Step 10: Set Up Queue Workers (if using queues)

```bash
# Create systemd service
sudo nano /etc/systemd/system/laravel-worker.service
```

```ini
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/ecommerce/artisan queue:work --sleep=3 --tries=3

[Install]
WantedBy=multi-user.target
```

```bash
# Enable and start service
sudo systemctl enable laravel-worker
sudo systemctl start laravel-worker
```

### Step 11: Set Up Scheduled Tasks (Cron)

```bash
# Edit crontab
sudo crontab -e -u www-data

# Add Laravel scheduler
* * * * * cd /var/www/ecommerce && php artisan schedule:run >> /dev/null 2>&1
```

## Post-Deployment

### Verify Deployment

1. **Check Application**
   - Visit website
   - Test checkout process
   - Test admin panel

2. **Check Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Check Performance**
   - Monitor response times
   - Check database queries
   - Monitor memory usage

### Monitoring Setup

1. **Enable Performance Logging** (optional)
   ```env
   ENABLE_PERFORMANCE_LOGGING=true
   ENABLE_SLOW_QUERY_LOGGING=true
   ```

2. **Set Up Error Tracking**
   - Configure Sentry (see `SENTRY_SETUP.md`)
   - Set up alerts

3. **Set Up Log Aggregation**
   - Configure log rotation
   - Set up log monitoring

## Backup Strategy

### Database Backup

```bash
# Create backup script
nano /usr/local/bin/backup-database.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u username -p password database_name > /backups/db_$DATE.sql
find /backups -name "db_*.sql" -mtime +7 -delete
```

```bash
# Make executable
chmod +x /usr/local/bin/backup-database.sh

# Add to crontab (daily at 2 AM)
0 2 * * * /usr/local/bin/backup-database.sh
```

### File Backup

```bash
# Backup storage directory
tar -czf /backups/storage_$(date +%Y%m%d).tar.gz storage/
```

## Maintenance

### Regular Tasks

1. **Clear Old Logs**
   ```bash
   # Logs are automatically rotated, but you can manually clean:
   find storage/logs -name "*.log" -mtime +30 -delete
   ```

2. **Update Dependencies**
   ```bash
   composer update
   npm update
   ```

3. **Run Migrations**
   ```bash
   php artisan migrate
   ```

4. **Clear Caches**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

### Updating Application

```bash
# Pull latest changes
git pull origin main

# Install new dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

## Troubleshooting

### Common Issues

**Issue**: 500 Internal Server Error
- Check file permissions
- Check `.env` configuration
- Check error logs: `storage/logs/laravel.log`

**Issue**: Assets not loading
- Run `php artisan storage:link`
- Run `npm run build`
- Check web server configuration

**Issue**: Database connection errors
- Verify database credentials in `.env`
- Check database server is running
- Verify database user has proper permissions

**Issue**: Slow performance
- Enable OPcache
- Use Redis for caching
- Check database indexes
- Monitor slow queries

## Security Hardening

### File Permissions

```bash
# Set proper permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
```

### Environment Security

- Never commit `.env` file
- Use strong database passwords
- Enable HTTPS only
- Set secure session configuration

### Server Security

- Keep system updated
- Configure firewall
- Use fail2ban
- Regular security audits

---

**Last Updated**: [Current Date]
