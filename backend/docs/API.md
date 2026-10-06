# API Documentation

## Overview

This document describes the API endpoints available in the e-commerce application.

## Base URL

```
http://your-domain.com/api
```

## Authentication

Currently, the application uses web-based authentication. API authentication can be implemented using Laravel Sanctum.

## Endpoints

### Products

#### Get All Products

```
GET /api/products
```

**Response**:
```json
{
  "data": [
    {
      "id": 1,
      "name_en": "Product Name",
      "name_ar": "اسم المنتج",
      "price": 100.00,
      "quantity": 50,
      "discount": 0.1,
      "is_available": true,
      "category": {
        "id": 1,
        "name_en": "Category"
      }
    }
  ]
}
```

#### Get Product by ID

```
GET /api/products/{id}
```

**Response**:
```json
{
  "data": {
    "id": 1,
    "name_en": "Product Name",
    "price": 100.00,
    "quantity": 50
  }
}
```

### Cart

#### Add to Cart

```
POST /api/cart
```

**Request**:
```json
{
  "id": 1,
  "name": "Product Name",
  "price": 100.00,
  "quantity": 2,
  "photo": "path/to/photo.jpg"
}
```

**Response**:
```json
{
  "success": true,
  "message": "Product added to cart successfully!"
}
```

#### Get Cart Items

```
GET /api/cart
```

**Response**:
```json
{
  "data": [
    {
      "id": "1",
      "name": "Product Name",
      "price": 100.00,
      "quantity": 2,
      "photo": "path/to/photo.jpg"
    }
  ],
  "total": 200.00
}
```

#### Update Cart Item

```
PUT /api/cart/{id}
```

**Request**:
```json
{
  "quantity": 3
}
```

#### Remove from Cart

```
DELETE /api/cart/{id}
```

### Orders

#### Create Order

```
POST /api/orders
```

**Request**:
```json
{
  "costumer_name": "John Doe",
  "costumer_number": "1234567890",
  "address": "123 Main St",
  "total": 200.00,
  "note": "Please deliver in the morning"
}
```

**Response**:
```json
{
  "success": true,
  "message": "Order placed successfully!",
  "order_id": 123
}
```

#### Get Order by ID

```
GET /api/orders/{id}
```

**Response**:
```json
{
  "data": {
    "id": 123,
    "costumer_name": "John Doe",
    "total": 200.00,
    "order_status": 0,
    "orderitems": [
      {
        "product_id": 1,
        "quantity": 2
      }
    ]
  }
}
```

## Error Responses

### Validation Error

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "costumer_name": ["The costumer name field is required."],
    "costumer_number": ["The costumer number must be numeric."]
  }
}
```

### Not Found Error

```json
{
  "message": "Product not found.",
  "error": "The requested product does not exist or has been removed."
}
```

### Insufficient Stock Error

```json
{
  "message": "Insufficient stock. Requested: 10, Available: 5",
  "available_quantity": 5,
  "error": "Insufficient stock available for this product."
}
```

## Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Rate Limiting

- API endpoints: 60 requests per minute per user/IP
- Cart operations: 30 requests per minute
- Checkout: 5 requests per minute

---

**Note**: Full API implementation with authentication is recommended for production use.
