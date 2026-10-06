# Developer Guide

## Coding Standards

### PSR-12 Compliance

The project follows PSR-12 coding standards. Use PHP CS Fixer to ensure compliance:

```bash
./vendor/bin/php-cs-fixer fix
```

### Naming Conventions

- **Classes**: PascalCase (`ProductService`, `OrderRepository`)
- **Methods**: camelCase (`getAll()`, `createOrder()`)
- **Variables**: camelCase (`$productName`, `$orderTotal`)
- **Constants**: UPPER_SNAKE_CASE (`LOW_STOCK_THRESHOLD`)

### Code Organization

```
app/
├── Constants/      # Application constants
├── DTOs/          # Data transfer objects
├── Events/        # Application events
├── Exceptions/    # Custom exceptions
├── Http/          # HTTP layer
├── Models/        # Eloquent models
├── Policies/      # Authorization policies
├── Repositories/  # Data access layer
└── Services/      # Business logic layer
```

## Development Workflow

### 1. Create Feature Branch

```bash
git checkout -b feature/your-feature-name
```

### 2. Make Changes

- Follow coding standards
- Write PHPDoc comments
- Add error handling
- Update tests

### 3. Test Your Changes

```bash
# Run tests
php artisan test

# Check code style
./vendor/bin/php-cs-fixer fix --dry-run
```

### 4. Commit Changes

```bash
git add .
git commit -m "feat: Add new feature description"
```

### 5. Push and Create PR

```bash
git push origin feature/your-feature-name
```

## Adding New Features

### Step 1: Create Model and Migration

```bash
php artisan make:model Product -m
```

### Step 2: Create Repository

```bash
# Create interface
# app/Repositories/Contracts/ProductRepositoryInterface.php

# Create implementation
# app/Repositories/ProductRepository.php
```

### Step 3: Create Service

```bash
# app/Services/ProductService.php
```

### Step 4: Create DTO (if needed)

```bash
# app/DTOs/ProductDTO.php
```

### Step 5: Create Controller

```bash
php artisan make:controller ProductController
```

### Step 6: Create Routes

```php
// routes/web.php
Route::resource('products', ProductController::class);
```

### Step 7: Create Views

```bash
# resources/views/products/
```

## Service Layer Guidelines

### Service Responsibilities

- Business logic
- Transaction management
- Event dispatching
- Cache management
- Error handling

### Service Example

```php
class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected LoggingService $loggingService
    ) {}

    public function create(ProductDTO $dto, ?UploadedFile $photo = null)
    {
        // Business logic here
        $product = $this->productRepository->create($data);
        
        // Log operation
        $this->loggingService->logProduct('info', 'Product created', [
            'product_id' => $product->id,
        ]);
        
        // Dispatch event
        event(new ProductCreated($product));
        
        return $product;
    }
}
```

## Repository Pattern Guidelines

### Repository Responsibilities

- Database queries
- Query optimization
- Eager loading
- Data transformation

### Repository Example

```php
class ProductRepository implements ProductRepositoryInterface
{
    public function getAvailableProducts(array $relations = []): Collection
    {
        $query = Product::where('is_available', true)
            ->where('quantity', '>', 0);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }
}
```

## DTO Guidelines

### When to Use DTOs

- Transferring data between layers
- API responses
- Complex data structures
- Type safety

### DTO Example

```php
class ProductDTO
{
    public function __construct(
        public readonly string $nameEn,
        public readonly float $price,
        public readonly int $quantity,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nameEn: $data['name_en'],
            price: (float) $data['price'],
            quantity: (int) $data['quantity'],
        );
    }

    public function toArray(): array
    {
        return [
            'name_en' => $this->nameEn,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];
    }
}
```

## Event-Driven Development

### Creating Events

```php
// app/Events/ProductUpdated.php
class ProductUpdated
{
    public function __construct(
        public Product $product
    ) {}
}
```

### Creating Listeners

```php
// app/Listeners/UpdateProductCache.php
class UpdateProductCache
{
    public function handle(ProductUpdated $event): void
    {
        Cache::forget("product_{$event->product->id}");
    }
}
```

### Registering Events

```php
// app/Providers/EventServiceProvider.php
protected $listen = [
    ProductUpdated::class => [
        UpdateProductCache::class,
    ],
];
```

## Logging Guidelines

### Using LoggingService

```php
// Log order event
$this->loggingService->logOrder('info', 'Order created', [
    'order_id' => $order->id,
]);

// Log product event
$this->loggingService->logProduct('error', 'Product update failed', [
    'product_id' => $id,
    'error' => $e->getMessage(),
]);
```

### Log Levels

- `emergency` - System is unusable
- `alert` - Action must be taken immediately
- `critical` - Critical conditions
- `error` - Error conditions
- `warning` - Warning conditions
- `notice` - Normal but significant condition
- `info` - Informational messages
- `debug` - Debug-level messages

## Testing Guidelines

### Unit Tests

Test individual components in isolation:

```php
class ProductServiceTest extends TestCase
{
    public function test_can_create_product()
    {
        $repository = Mockery::mock(ProductRepositoryInterface::class);
        $service = new ProductService($repository);
        
        // Test logic
    }
}
```

### Feature Tests

Test complete workflows:

```php
class OrderCreationTest extends TestCase
{
    public function test_user_can_create_order()
    {
        $response = $this->post('/check', [
            'costumer_name' => 'John Doe',
            // ... other fields
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'costumer_name' => 'John Doe',
        ]);
    }
}
```

## Git Workflow

### Branch Naming

- `feature/` - New features
- `bugfix/` - Bug fixes
- `hotfix/` - Critical fixes
- `refactor/` - Code refactoring

### Commit Messages

Follow conventional commits:

```
feat: Add product search functionality
fix: Fix cart total calculation
refactor: Extract order validation logic
docs: Update API documentation
test: Add tests for ProductService
```

## Code Review Checklist

- [ ] Code follows PSR-12 standards
- [ ] PHPDoc comments added
- [ ] Error handling implemented
- [ ] Tests written and passing
- [ ] No hard-coded values (use constants)
- [ ] No N+1 queries
- [ ] Proper use of eager loading
- [ ] Cache invalidation handled
- [ ] Logging added for important operations
- [ ] Security considerations addressed

## Performance Guidelines

### Database Queries

- Use eager loading: `with(['relation1', 'relation2'])`
- Use indexes on frequently queried columns
- Use query scopes for reusable queries
- Avoid `N+1` queries

### Caching

- Cache static data (categories, types, etc.)
- Use appropriate TTL values
- Invalidate cache on updates
- Use cache tags when possible

### Code Optimization

- Use pagination for large datasets
- Limit columns with `select()`
- Use database transactions for multi-step operations
- Monitor slow queries

## Security Guidelines

### Input Validation

- Always validate user input
- Use Form Request classes
- Sanitize data before storing

### Authorization

- Use policies for authorization
- Check permissions in controllers
- Don't trust client-side validation

### SQL Injection Prevention

- Use Eloquent ORM (automatically prevents SQL injection)
- Use parameterized queries if using raw SQL
- Never concatenate user input in queries

## Debugging

### Enable Debug Mode

```env
APP_DEBUG=true
```

### View Logs

```bash
tail -f storage/logs/laravel.log
tail -f storage/logs/orders.log
```

### Use Tinker

```bash
php artisan tinker

# Example
$product = Product::find(1);
$product->category;
```

## Common Tasks

### Clear All Caches

```bash
php artisan optimize:clear
```

### Run Migrations

```bash
php artisan migrate
php artisan migrate:rollback
```

### Generate IDE Helper

```bash
php artisan ide-helper:generate
php artisan ide-helper:models
```

---

**Last Updated**: [Current Date]
