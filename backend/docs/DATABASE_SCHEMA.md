# Database Schema Documentation

## Overview

This document describes the database schema, relationships, and indexes for the e-commerce application.

## Entity Relationship Diagram

```
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│  Category   │◄────────┤   Product    ├─────────►│    Type     │
└─────────────┘         └──────────────┘         └─────────────┘
                               │
                               │
                               │
                        ┌──────▼──────┐
                        │ Manfacturer │
                        └─────────────┘

┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│   Order     │◄────────┤  OrderItem  ├─────────►│  Product    │
└─────────────┘         └──────────────┘         └─────────────┘
      │
      │
      ▼
┌─────────────┐
│   Coupon    │
└─────────────┘
```

## Tables

### products

Stores product information.

| Column | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| name_en | varchar(255) | Product name (English) |
| name_ar | varchar(255) | Product name (Arabic) |
| description_en | text | Description (English) |
| description_ar | text | Description (Arabic) |
| price | decimal(10,2) | Product price |
| quantity | int | Stock quantity |
| discount | decimal(3,2) | Discount percentage (0-1) |
| photo | varchar(255) | Photo path |
| is_available | boolean | Availability status |
| category_id | bigint | Foreign key to categories |
| type_id | bigint | Foreign key to types |
| manfacturer_id | bigint | Foreign key to manufacturers |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

**Indexes**:
- `products_category_id_index` (category_id)
- `products_type_id_index` (type_id)
- `products_manfacturer_id_index` (manfacturer_id)
- `products_available_index` (is_available, quantity) - Composite
- `products_discount_index` (discount)

### orders

Stores order information.

| Column | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| costumer_name | varchar(255) | Customer name |
| costumer_number | varchar(20) | Customer phone number |
| address | text | Delivery address |
| total | decimal(10,2) | Order total |
| order_status | int | Order status (0=Pending, 1=Received, 2=Delivered) |
| note | text | Order notes |
| coupon_id | bigint | Foreign key to coupons (nullable) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

**Indexes**:
- `orders_order_status_index` (order_status)
- `orders_coupon_id_index` (coupon_id)

### orderitems

Stores order item information.

| Column | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| order_id | bigint | Foreign key to orders |
| product_id | bigint | Foreign key to products |
| quantity | int | Item quantity |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

**Indexes**:
- `orderitems_order_id_index` (order_id)
- `orderitems_product_id_index` (product_id)

### categories

Stores category information.

| Column | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| name_en | varchar(255) | Category name (English) |
| name_ar | varchar(255) | Category name (Arabic) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### types

Stores product type information.

| Column | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| name_en | varchar(255) | Type name (English) |
| name_ar | varchar(255) | Type name (Arabic) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### manfacturers

Stores manufacturer information.

| Column | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| name_en | varchar(255) | Manufacturer name (English) |
| name_ar | varchar(255) | Manufacturer name (Arabic) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### coupons

Stores coupon/discount information.

| Column | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| code | varchar(50) | Coupon code |
| discount | decimal(5,2) | Discount amount/percentage |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

## Relationships

### Product Relationships

- `Product` belongsTo `Category` (category_id)
- `Product` belongsTo `Type` (type_id)
- `Product` belongsTo `Manfacturer` (manfacturer_id)
- `Product` hasMany `Orderitem` (product_id)

### Order Relationships

- `Order` hasMany `Orderitem` (order_id)
- `Order` belongsTo `Coupon` (coupon_id, nullable)

### OrderItem Relationships

- `Orderitem` belongsTo `Order` (order_id)
- `Orderitem` belongsTo `Product` (product_id)

## Indexes Summary

### Performance Indexes

All indexes are created via migration: `2026_02_17_085618_add_performance_indexes_to_tables.php`

**Products Table**:
- Category filtering
- Type filtering
- Manufacturer filtering
- Available products query (composite)
- Discount sorting

**Orders Table**:
- Status filtering
- Coupon filtering

**OrderItems Table**:
- Order filtering
- Product filtering

## Query Optimization

### Eager Loading

Always use eager loading to prevent N+1 queries:

```php
// Good
Product::with(['category', 'type', 'manfacturer'])->get();

// Bad (N+1 query)
Product::all(); // Then accessing $product->category
```

### Query Scopes

Use query scopes for common queries:

```php
// Available products
Product::available()->get();

// Low stock products
Product::lowStock(10)->get();

// By category
Product::byCategory($categoryId)->get();
```

## Data Integrity

### Foreign Key Constraints

Foreign keys ensure referential integrity:
- `products.category_id` → `categories.id`
- `products.type_id` → `types.id`
- `products.manfacturer_id` → `manfacturers.id`
- `orders.coupon_id` → `coupons.id`
- `orderitems.order_id` → `orders.id`
- `orderitems.product_id` → `products.id`

### Constraints

- `order_status` should be 0, 1, or 2 (use OrderStatus constants)
- `discount` should be between 0 and 1
- `quantity` should be >= 0
- `price` should be >= 0

---

**Last Updated**: [Current Date]
