# Subshero API Documentation

Subshero provides a comprehensive RESTful API that allows you to integrate with other systems and automate subscription management tasks. This document outlines the available endpoints, authentication methods, and provides examples for common operations.

## API Overview

The Subshero API follows RESTful principles and uses standard HTTP methods:

- `GET` - Retrieve resources
- `POST` - Create resources
- `PUT/PATCH` - Update resources
- `DELETE` - Delete resources

All API responses are in JSON format.

## Base URL

All API endpoints are prefixed with `/api/v1/`:

```
https://your-domain.com/api/v1/
```

## Authentication

The Subshero API uses token-based authentication via Laravel Sanctum. To use the API, you need to:

1. Generate an API token in the Subshero application
2. Include the token in your API requests

### Generating an API Token

1. Log in to your Subshero account
2. Go to **Settings > API**
3. Click "Create New Token"
4. Enter a name for your token
5. Copy the generated token (it will only be shown once)

### Using the API Token

Include the token in the `Authorization` header of your requests:

```
Authorization: Bearer your-api-token
```

### Authentication Endpoints

#### Login

```
POST /api/v1/auth/login
```

Request body:
```json
{
  "email": "your-email@example.com",
  "password": "your-password"
}
```

Response:
```json
{
  "status": true,
  "message": "Login successful",
  "data": {
    "token": "your-api-token",
    "user": {
      "id": 1,
      "name": "Your Name",
      "email": "your-email@example.com"
    }
  }
}
```

#### Register

```
POST /api/v1/auth/register
```

Request body:
```json
{
  "name": "Your Name",
  "email": "your-email@example.com",
  "password": "your-password",
  "password_confirmation": "your-password"
}
```

Response:
```json
{
  "status": true,
  "message": "Registration successful",
  "data": {
    "token": "your-api-token",
    "user": {
      "id": 1,
      "name": "Your Name",
      "email": "your-email@example.com"
    }
  }
}
```

#### Logout

```
GET /api/v1/auth/logout
```

Response:
```json
{
  "status": true,
  "message": "Logged out successfully"
}
```

## API Endpoints

### Subscriptions

#### List Subscriptions

```
GET /api/v1/subscriptions
```

Query parameters:
- `page` - Page number for pagination
- `limit` - Number of items per page
- `search` - Search term
- `folder_id` - Filter by folder ID
- `status` - Filter by status (0=Draft, 1=Active, 2=Cancel)
- `type` - Filter by type (1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue)

Response:
```json
{
  "status": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "user_id": 1,
        "folder_id": 1,
        "brand_id": 1,
        "type": 1,
        "product_name": "Example Subscription",
        "price": 9.99,
        "price_type": "USD",
        "payment_date": "2023-01-01",
        "next_payment_date": "2023-02-01",
        "status": 1
      }
    ],
    "first_page_url": "https://your-domain.com/api/v1/subscriptions?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "https://your-domain.com/api/v1/subscriptions?page=1",
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "https://your-domain.com/api/v1/subscriptions?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "next_page_url": null,
    "path": "https://your-domain.com/api/v1/subscriptions",
    "per_page": 15,
    "prev_page_url": null,
    "to": 1,
    "total": 1
  }
}
```

#### Get Subscription

```
GET /api/v1/subscriptions/{id}
```

Response:
```json
{
  "status": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "folder_id": 1,
    "brand_id": 1,
    "type": 1,
    "product_name": "Example Subscription",
    "price": 9.99,
    "price_type": "USD",
    "payment_date": "2023-01-01",
    "next_payment_date": "2023-02-01",
    "status": 1,
    "folder": {
      "id": 1,
      "name": "Example Folder",
      "color": "#FF0000"
    },
    "tags": [
      {
        "id": 1,
        "name": "Example Tag"
      }
    ]
  }
}
```

#### Create Subscription

```
POST /api/v1/subscriptions
```

Request body:
```json
{
  "status": 1,
  "type": 1,
  "folder_id": 1,
  "brand_id": 1,
  "alert_id": 1,
  "product_name": "Example Subscription",
  "description": "Example description",
  "price": 9.99,
  "price_type": "USD",
  "payment_mode_id": 1,
  "payment_date": "2023-01-01",
  "recurring": 1,
  "billing_frequency": 1,
  "billing_cycle": 3,
  "tags": [1, 2]
}
```

Response:
```json
{
  "status": true,
  "message": "Subscription created successfully",
  "data": {
    "id": 1
  }
}
```

#### Update Subscription

```
PUT /api/v1/subscriptions/{id}
```

Request body:
```json
{
  "status": 1,
  "type": 1,
  "folder_id": 1,
  "brand_id": 1,
  "alert_id": 1,
  "product_name": "Updated Subscription",
  "description": "Updated description",
  "price": 19.99,
  "price_type": "USD",
  "payment_mode_id": 1,
  "payment_date": "2023-01-01",
  "recurring": 1,
  "billing_frequency": 1,
  "billing_cycle": 3,
  "tags": [1, 2, 3]
}
```

Response:
```json
{
  "status": true,
  "message": "Subscription updated successfully"
}
```

#### Delete Subscription

```
DELETE /api/v1/subscriptions/{id}
```

Response:
```json
{
  "status": true,
  "message": "Subscription deleted successfully"
}
```

#### Cancel Subscription

```
POST /api/v1/subscriptions/{id}/cancel
```

Response:
```json
{
  "status": true,
  "message": "Subscription cancelled successfully"
}
```

#### Refund Subscription

```
POST /api/v1/subscriptions/{id}/refund
```

Response:
```json
{
  "status": true,
  "message": "Subscription refunded successfully"
}
```

### Folders

#### List Folders

```
GET /api/v1/folders
```

Response:
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "name": "Example Folder",
      "color": "#FF0000",
      "is_default": 0,
      "created_at": "2023-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### Get Folder

```
GET /api/v1/folders/{id}
```

Response:
```json
{
  "status": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "name": "Example Folder",
    "color": "#FF0000",
    "is_default": 0,
    "created_at": "2023-01-01T00:00:00.000000Z"
  }
}
```

#### Create Folder

```
POST /api/v1/folders
```

Request body:
```json
{
  "name": "New Folder",
  "color": "#00FF00"
}
```

Response:
```json
{
  "status": true,
  "message": "Folder created successfully",
  "data": {
    "id": 2
  }
}
```

#### Update Folder

```
PUT /api/v1/folders/{id}
```

Request body:
```json
{
  "name": "Updated Folder",
  "color": "#0000FF"
}
```

Response:
```json
{
  "status": true,
  "message": "Folder updated successfully"
}
```

#### Delete Folder

```
DELETE /api/v1/folders/{id}
```

Response:
```json
{
  "status": true,
  "message": "Folder deleted successfully"
}
```

### Tags

#### List Tags

```
GET /api/v1/tags
```

Response:
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "name": "Example Tag"
    }
  ]
}
```

#### Get Tag

```
GET /api/v1/tags/{id}
```

Response:
```json
{
  "status": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "name": "Example Tag"
  }
}
```

#### Create Tag

```
POST /api/v1/tags
```

Request body:
```json
{
  "name": "New Tag"
}
```

Response:
```json
{
  "status": true,
  "message": "Tag created successfully",
  "data": {
    "id": 2
  }
}
```

#### Update Tag

```
PUT /api/v1/tags/{id}
```

Request body:
```json
{
  "name": "Updated Tag"
}
```

Response:
```json
{
  "status": true,
  "message": "Tag updated successfully"
}
```

#### Delete Tag

```
DELETE /api/v1/tags/{id}
```

Response:
```json
{
  "status": true,
  "message": "Tag deleted successfully"
}
```

### Reports

#### Get Reports Overview

```
GET /api/v1/reports
```

Response:
```json
{
  "status": true,
  "data": {
    "active_subscriptions": 10,
    "active_subscriptions_value": 99.90,
    "active_lifetime": 5,
    "active_lifetime_value": 249.95,
    "monthly_recurring_revenue": 99.90,
    "yearly_recurring_revenue": 1198.80
  }
}
```

#### Get Active Subscriptions and Lifetime Purchases

```
GET /api/v1/reports/active-subs-ltd
```

Response:
```json
{
  "status": true,
  "data": {
    "subscriptions": [
      {
        "id": 1,
        "product_name": "Example Subscription",
        "price": 9.99,
        "price_type": "USD",
        "payment_date": "2023-01-01",
        "next_payment_date": "2023-02-01"
      }
    ],
    "lifetime": [
      {
        "id": 2,
        "product_name": "Example Lifetime",
        "price": 49.99,
        "price_type": "USD",
        "payment_date": "2023-01-01"
      }
    ]
  }
}
```

#### Get Active Subscription Total

```
GET /api/v1/reports/active-subscription-total
```

Response:
```json
{
  "status": true,
  "data": {
    "count": 10,
    "value": 99.90
  }
}
```

#### Get Active Subscription MRR

```
GET /api/v1/reports/active-subscription-mrr
```

Response:
```json
{
  "status": true,
  "data": {
    "mrr": 99.90,
    "arr": 1198.80
  }
}
```

### Calendar

#### Get Calendar Events

```
GET /api/v1/calendar
```

Response:
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "title": "Example Subscription Renewal",
      "start": "2023-02-01",
      "end": "2023-02-01",
      "subscription_id": 1,
      "type": "renewal"
    }
  ]
}
```

### Notifications

#### List Notifications

```
GET /api/v1/notifications
```

Response:
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "type": "subscription_renew",
      "title": "Subscription Renewal",
      "message": "Your subscription will renew in 3 days",
      "created_at": "2023-01-28T00:00:00.000000Z"
    }
  ]
}
```

#### Mark Notification as Read

```
PUT /api/v1/notifications/{id}
```

Response:
```json
{
  "status": true,
  "message": "Notification marked as read"
}
```

#### Delete Notification

```
DELETE /api/v1/notifications/{id}
```

Response:
```json
{
  "status": true,
  "message": "Notification deleted"
}
```

#### Clear All Notifications

```
DELETE /api/v1/notifications
```

Response:
```json
{
  "status": true,
  "message": "All notifications cleared"
}
```

## Error Handling

The API returns appropriate HTTP status codes along with error messages in the response body:

```json
{
  "status": false,
  "message": "Error message",
  "errors": {
    "field_name": [
      "Validation error message"
    ]
  }
}
```

Common HTTP status codes:

- `200 OK` - The request was successful
- `201 Created` - The resource was successfully created
- `400 Bad Request` - The request was invalid
- `401 Unauthorized` - Authentication failed
- `403 Forbidden` - The authenticated user doesn't have permission
- `404 Not Found` - The requested resource doesn't exist
- `422 Unprocessable Entity` - Validation failed
- `500 Internal Server Error` - Server error

## Rate Limiting

The API has a rate limit of 1000 requests per minute per user. If you exceed this limit, you'll receive a `429 Too Many Requests` response.

## Examples

### cURL Examples

#### Authentication

```bash
# Login
curl -X POST https://your-domain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"your-email@example.com","password":"your-password"}'

# Using token
curl -X GET https://your-domain.com/api/v1/subscriptions \
  -H "Authorization: Bearer your-api-token"
```

#### Subscriptions

```bash
# List subscriptions
curl -X GET https://your-domain.com/api/v1/subscriptions \
  -H "Authorization: Bearer your-api-token"

# Create subscription
curl -X POST https://your-domain.com/api/v1/subscriptions \
  -H "Authorization: Bearer your-api-token" \
  -H "Content-Type: application/json" \
  -d '{
    "status": 1,
    "type": 1,
    "folder_id": 1,
    "brand_id": 1,
    "alert_id": 1,
    "product_name": "Example Subscription",
    "description": "Example description",
    "price": 9.99,
    "price_type": "USD",
    "payment_mode_id": 1,
    "payment_date": "2023-01-01",
    "recurring": 1,
    "billing_frequency": 1,
    "billing_cycle": 3,
    "tags": [1, 2]
  }'
```

### PHP Examples

```php
<?php

$apiToken = 'your-api-token';
$baseUrl = 'https://your-domain.com/api/v1';

// List subscriptions
$ch = curl_init("$baseUrl/subscriptions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiToken",
    "Accept: application/json"
]);
$response = curl_exec($ch);
$subscriptions = json_decode($response, true);
curl_close($ch);

// Create subscription
$subscriptionData = [
    'status' => 1,
    'type' => 1,
    'folder_id' => 1,
    'brand_id' => 1,
    'alert_id' => 1,
    'product_name' => 'Example Subscription',
    'description' => 'Example description',
    'price' => 9.99,
    'price_type' => 'USD',
    'payment_mode_id' => 1,
    'payment_date' => '2023-01-01',
    'recurring' => 1,
    'billing_frequency' => 1,
    'billing_cycle' => 3,
    'tags' => [1, 2]
];

$ch = curl_init("$baseUrl/subscriptions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($subscriptionData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiToken",
    "Content-Type: application/json",
    "Accept: application/json"
]);
$response = curl_exec($ch);
$result = json_decode($response, true);
curl_close($ch);
```

### JavaScript Examples

```javascript
const apiToken = 'your-api-token';
const baseUrl = 'https://your-domain.com/api/v1';

// List subscriptions
fetch(`${baseUrl}/subscriptions`, {
  headers: {
    'Authorization': `Bearer ${apiToken}`,
    'Accept': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));

// Create subscription
const subscriptionData = {
  status: 1,
  type: 1,
  folder_id: 1,
  brand_id: 1,
  alert_id: 1,
  product_name: 'Example Subscription',
  description: 'Example description',
  price: 9.99,
  price_type: 'USD',
  payment_mode_id: 1,
  payment_date: '2023-01-01',
  recurring: 1,
  billing_frequency: 1,
  billing_cycle: 3,
  tags: [1, 2]
};

fetch(`${baseUrl}/subscriptions`, {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${apiToken}`,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify(subscriptionData)
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

## Conclusion

This documentation covers the main endpoints and functionality of the Subshero API. For more detailed information or specific use cases, please contact the Subshero support team.