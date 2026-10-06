# Architecture Documentation

## Overview

This document describes the architecture of the e-commerce application, including system design, component relationships, and data flow.

## System Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Frontend (Blade)                      │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐             │
│  │  Public  │  │  Admin   │  │   API    │             │
│  └──────────┘  └──────────┘  └──────────┘             │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│              HTTP Layer (Controllers)                    │
│  ┌──────────────┐  ┌──────────────┐                    │
│  │ FrontendCtrl │  │ ProductCtrl  │                    │
│  │ CartCtrl     │  │ OrderCtrl    │                    │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│            Business Logic Layer (Services)               │
│  ┌──────────────┐  ┌──────────────┐                    │
│  │ProductService│  │ OrderService │                    │
│  │InventorySvc  │  │ LoggingSvc   │                    │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│         Data Access Layer (Repositories)                 │
│  ┌──────────────┐  ┌──────────────┐                    │
│  │ProductRepo   │  │ OrderRepo    │                    │
│  │CategoryRepo  │  │              │                    │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│              Data Layer (Models/Database)                │
│  ┌──────────────┐  ┌──────────────┐                    │
│  │   Product    │  │    Order     │                    │
│  │   Category   │  │  OrderItem   │                    │
└─────────────────────────────────────────────────────────┘
```

## Layer Responsibilities

### 1. Controllers (HTTP Layer)

**Responsibility**: Handle HTTP requests and responses only

**Location**: `app/Http/Controllers/`

**Responsibilities**:
- Validate incoming requests
- Call appropriate services
- Format responses
- Handle HTTP status codes

**Example**:
```php
class ProductController extends Controller
{
    public function store(StoreProductRequest $request)
    {
        $dto = ProductDTO::fromArray($request->all());
        $product = $this->productService->create($dto, $request->file('photo'));
        return redirect()->back();
    }
}
```

### 2. Services (Business Logic Layer)

**Responsibility**: Implement business logic and orchestrate operations

**Location**: `app/Services/`

**Key Services**:
- `ProductService` - Product business logic
- `OrderService` - Order processing
- `InventoryService` - Stock management
- `LoggingService` - Structured logging

**Responsibilities**:
- Business rule validation
- Transaction management
- Event dispatching
- Cache management
- Error handling

**Example**:
```php
class OrderService
{
    public function createOrder(OrderDTO $dto, array $cartItems): Order
    {
        $this->validateCartNotEmpty($cartItems);
        $this->validateStockAvailability($cartItems);
        
        return DB::transaction(function () use ($dto, $cartItems) {
            $order = $this->orderRepository->create($dto->toArray());
            $this->processOrderItems($order, $cartItems);
            event(new OrderCreated($order));
            return $order;
        });
    }
}
```

### 3. Repositories (Data Access Layer)

**Responsibility**: Abstract database operations

**Location**: `app/Repositories/`

**Key Repositories**:
- `ProductRepository` - Product data access
- `OrderRepository` - Order data access
- `CategoryRepository` - Category data access

**Responsibilities**:
- Database queries
- Query optimization
- Eager loading
- Data transformation

**Example**:
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

### 4. Models (Data Layer)

**Responsibility**: Represent database entities and relationships

**Location**: `app/Models/`

**Key Models**:
- `Product` - Products
- `Order` - Orders
- `Orderitem` - Order items
- `Category` - Categories

**Features**:
- Eloquent relationships
- Query scopes
- Mass assignment protection
- Accessors/Mutators

## Data Flow Examples

### Order Creation Flow

```
1. User submits checkout form
   ↓
2. FrontendController::store()
   - Validates request (StoreOrderRequest)
   - Creates OrderDTO
   ↓
3. OrderService::createOrder()
   - Validates cart not empty
   - Validates stock availability
   - Starts database transaction
   ↓
4. OrderRepository::create()
   - Creates order record
   ↓
5. OrderService::processOrderItems()
   - Creates order items
   - Reserves stock via InventoryService
   ↓
6. Event: OrderCreated
   - Dispatched to listeners
   ↓
7. Listener: SendOrderConfirmationEmail
   - Logs order confirmation
   ↓
8. Response returned to user
```

### Product Creation Flow

```
1. Admin submits product form
   ↓
2. ProductController::store()
   - Validates request (StoreProductRequest)
   - Creates ProductDTO
   ↓
3. ProductService::create()
   - Handles photo upload (PhotoHandler)
   - Creates product via repository
   - Clears cache
   ↓
4. ProductRepository::create()
   - Inserts product record
   ↓
5. Event: ProductCreated
   - Dispatched to listeners
   ↓
6. Response returned
```

## Design Patterns Used

### 1. Repository Pattern

**Purpose**: Abstract data access layer

**Benefits**:
- Easier testing (can mock repositories)
- Database abstraction
- Reusable query logic

**Implementation**:
- Interfaces in `app/Repositories/Contracts/`
- Implementations in `app/Repositories/`

### 2. Service Layer Pattern

**Purpose**: Centralize business logic

**Benefits**:
- Separation of concerns
- Reusable business logic
- Easier to test

**Implementation**:
- Services in `app/Services/`
- Injected into controllers via dependency injection

### 3. DTO (Data Transfer Object) Pattern

**Purpose**: Type-safe data transfer

**Benefits**:
- Type safety
- Validation at boundaries
- Immutable data structures

**Implementation**:
- DTOs in `app/DTOs/`
- Used between controllers and services

### 4. Event-Driven Architecture

**Purpose**: Decouple components

**Benefits**:
- Loose coupling
- Easy to extend
- Async operations possible

**Implementation**:
- Events in `app/Events/`
- Listeners in `app/Listeners/`
- Registered in `EventServiceProvider`

## Database Schema

### Core Tables

```
products
├── id
├── name_en, name_ar
├── description_en, description_ar
├── price, quantity, discount
├── photo, is_available
├── category_id (FK)
├── type_id (FK)
└── manfacturer_id (FK)

orders
├── id
├── costumer_name, costumer_number
├── address, total, note
├── order_status
└── coupon_id (FK)

orderitems
├── id
├── order_id (FK)
├── product_id (FK)
└── quantity

categories
├── id
└── name_en, name_ar
```

### Relationships

- `Product` belongsTo `Category`, `Type`, `Manfacturer`
- `Product` hasMany `Orderitem`
- `Order` hasMany `Orderitem`
- `Order` belongsTo `Coupon`
- `Orderitem` belongsTo `Order`, `Product`

## Caching Strategy

### Cache Keys

- `categories` - TTL: 1 hour
- `types` - TTL: 1 hour
- `manfacturers` - TTL: 1 hour
- `sliders` - TTL: 1 hour
- `ads` - TTL: 1 hour
- `product_statistics` - TTL: 5 minutes

### Cache Invalidation

- Product create/update/delete → Clear `product_statistics`
- Category/Type/Manufacturer changes → Clear respective cache

## Security Architecture

### Authentication

- Laravel Breeze for authentication
- Session-based authentication
- CSRF protection on all forms

### Authorization

- Policy-based authorization
- Policies: `ProductPolicy`, `OrderPolicy`, `UserPolicy`
- Registered in `AuthServiceProvider`

### Rate Limiting

- Cart operations: 30 requests/minute
- Checkout: 5 requests/minute
- API: 60 requests/minute

## Performance Optimizations

### Database

- Indexes on frequently queried columns
- Eager loading to prevent N+1 queries
- Query scopes for reusable queries

### Caching

- Static data cached (categories, types, etc.)
- Statistics cached with shorter TTL
- Cache invalidation on updates

### Code

- Service layer for reusable logic
- Repository pattern for optimized queries
- DTOs for efficient data transfer

## Logging Architecture

### Log Channels

- `orders` - Order events
- `products` - Product events
- `cart` - Cart operations
- `inventory` - Inventory events
- `performance` - Performance metrics
- `security` - Security events

### Logging Service

Centralized logging with automatic context enrichment:
- User ID
- IP address
- Timestamp
- User agent

## Event System

### Events

- `OrderCreated` - When order is placed
- `OrderStatusChanged` - When order status updates
- `ProductStockLow` - When stock is low
- `ProductCreated` - When product is created

### Listeners

- `SendOrderConfirmationEmail` - Handles order creation
- `NotifyAdminLowStock` - Handles low stock alerts

## Extension Points

### Adding New Features

1. **Create Model** (if needed)
2. **Create Migration**
3. **Create Repository Interface & Implementation**
4. **Create Service**
5. **Create DTO** (if needed)
6. **Create Controller**
7. **Create Routes**
8. **Create Views** (if needed)
9. **Add Tests**

### Adding New Events

1. **Create Event** in `app/Events/`
2. **Create Listener** in `app/Listeners/`
3. **Register** in `EventServiceProvider`
4. **Dispatch** event in service

---

**Last Updated**: [Current Date]
