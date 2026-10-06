# E-Commerce Application

A modern, bilingual (English/Arabic) e-commerce application built with Laravel 11, featuring a complete shopping cart system, order management, and admin panel.

## 🚀 Features

- **Bilingual Support**: English and Arabic interface
- **Shopping Cart**: Full-featured cart with session-based storage
- **Order Management**: Complete order processing with status tracking
- **Product Management**: CRUD operations for products with categories, types, and manufacturers
- **Inventory Management**: Stock tracking and low stock alerts
- **Admin Panel**: Comprehensive admin interface for managing all aspects of the store
- **Performance Optimized**: Database indexes, caching, and query optimization
- **Monitoring & Logging**: Comprehensive logging and performance monitoring
- **Modern Architecture**: Service layer, repository pattern, DTOs, and event-driven architecture

## 📋 Requirements

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM (for frontend assets)
- Web server (Apache/Nginx)

## 🔧 Installation

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd "new ecomm project"
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Environment Variables

Edit `.env` file with your configuration:

```env
APP_NAME="E-Commerce"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Optional: Performance Monitoring
ENABLE_PERFORMANCE_LOGGING=false
ENABLE_SLOW_QUERY_LOGGING=false
SLOW_QUERY_THRESHOLD=0.1

# Optional: Sentry Error Tracking
SENTRY_LARAVEL_DSN=
SENTRY_ENVIRONMENT=production
```

### Step 5: Database Setup

```bash
# Run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### Step 6: Storage Setup

```bash
# Create storage link
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

### Step 7: Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### Step 8: Run the Application

```bash
# Start development server
php artisan serve

# Or use Laravel Sail (Docker)
./vendor/bin/sail up
```

Visit `http://localhost:8000` in your browser.

## 🏗️ Architecture

The application follows a modern, layered architecture:

```
Controller (HTTP Layer)
    ↓
Service (Business Logic)
    ↓
Repository (Data Access)
    ↓
Model (Eloquent)
```

### Key Components

- **Services**: Business logic layer (`app/Services/`)
- **Repositories**: Data access layer (`app/Repositories/`)
- **DTOs**: Data transfer objects (`app/DTOs/`)
- **Events & Listeners**: Event-driven architecture (`app/Events/`, `app/Listeners/`)
- **Policies**: Authorization policies (`app/Policies/`)
- **Constants**: Application constants (`app/Constants/`)

## 📁 Project Structure

```
app/
├── Constants/          # Application constants
├── DTOs/              # Data transfer objects
├── Events/            # Application events
├── Exceptions/        # Custom exceptions
├── Http/
│   ├── Controllers/   # HTTP controllers
│   ├── Middleware/    # HTTP middleware
│   └── Requests/      # Form request validation
├── Listeners/         # Event listeners
├── Models/            # Eloquent models
├── Policies/          # Authorization policies
├── Repositories/      # Repository pattern
└── Services/          # Business logic services

config/                # Configuration files
database/
├── migrations/        # Database migrations
└── seeders/          # Database seeders

resources/
└── views/            # Blade templates

routes/
├── web.php          # Web routes
└── api.php          # API routes
```

## 🔐 Default Credentials

After seeding, you can use these credentials (if seeders are configured):

- **Email**: admin@example.com
- **Password**: password

**⚠️ Change these in production!**

## 🛠️ Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
# Check code style
./vendor/bin/php-cs-fixer fix --dry-run

# Fix code style
./vendor/bin/php-cs-fixer fix
```

### Database Migrations

```bash
# Create migration
php artisan make:migration create_example_table

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback
```

### Cache Management

```bash
# Clear all caches
php artisan optimize:clear

# Clear specific cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 📚 Documentation

- [Architecture Documentation](docs/ARCHITECTURE.md)
- [API Documentation](docs/API.md)
- [Developer Guide](docs/DEVELOPER_GUIDE.md)
- [Deployment Guide](docs/DEPLOYMENT.md)

## 🔍 Monitoring & Logging

### Log Files

Logs are stored in `storage/logs/`:

- `laravel.log` - General application logs
- `orders.log` - Order-related events
- `products.log` - Product-related events
- `cart.log` - Cart operations
- `inventory.log` - Inventory events
- `performance.log` - Performance metrics
- `security.log` - Security events

### Performance Monitoring

Enable performance monitoring in `.env`:

```env
ENABLE_PERFORMANCE_LOGGING=true
ENABLE_SLOW_QUERY_LOGGING=true
SLOW_QUERY_THRESHOLD=0.1
```

## 🚀 Deployment

See [Deployment Guide](docs/DEPLOYMENT.md) for detailed deployment instructions.

### Quick Deployment Checklist

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `npm run build` for production assets
- [ ] Set up queue workers (if using queues)
- [ ] Configure web server (Apache/Nginx)
- [ ] Set up SSL certificate
- [ ] Configure backup strategy

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage
```

## 📦 Packages Used

- **Laravel Framework** ^11.0
- **Laravel Breeze** ^2.0 - Authentication
- **hnooz/laravel-shopping-cart** ^1.0 - Shopping cart
- **yoeunes/toastr** ^2.0 - Toast notifications

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 Code Standards

- Follow PSR-12 coding standards
- Use type hints and return types
- Write PHPDoc comments for all methods
- Follow Laravel conventions

## 🐛 Troubleshooting

### Common Issues

**Issue**: `Class not found` errors
- **Solution**: Run `composer dump-autoload`

**Issue**: Permission denied errors
- **Solution**: Set proper permissions: `chmod -R 775 storage bootstrap/cache`

**Issue**: 500 errors after deployment
- **Solution**: Clear caches: `php artisan optimize:clear`

**Issue**: Assets not loading
- **Solution**: Run `php artisan storage:link` and `npm run build`

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 Support

For support, email support@example.com or create an issue in the repository.

---

**Built with ❤️ using Laravel 11**
