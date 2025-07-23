# Facility Booking API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
This API uses Laravel Sanctum for authentication. Most endpoints require a Bearer token.

## Endpoints

### 1. Authentication

#### Register User
- **POST** `/register`
- **Description**: Register a new user
- **Body**:
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "user"
}
```
- **Response**:
```json
{
    "message": "User registered successfully",
    "token": "1|abc123...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
    }
}
```

#### Login
- **POST** `/login`
- **Description**: Login and get access token
- **Body**:
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```
- **Response**:
```json
{
    "token": "1|abc123...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
    }
}
```

#### Logout
- **POST** `/logout`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Logout and invalidate token
- **Response**:
```json
{
    "message": "Logged out successfully"
}
```

#### Get Current User
- **GET** `/user`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Get current authenticated user info
- **Response**:
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user"
}
```

### 2. Resources

#### Get All Resources
- **GET** `/resources`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Get all available resources
- **Response**:
```json
[
    {
        "id": 1,
        "name": "Conference Room A",
        "description": "Large conference room",
        "capacity": 20,
        "location": "Floor 1",
        "category_id": 1
    }
]
```

#### Get Single Resource
- **GET** `/resources/{id}`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Get a specific resource
- **Response**:
```json
{
    "id": 1,
    "name": "Conference Room A",
    "description": "Large conference room",
    "capacity": 20,
    "location": "Floor 1",
    "category_id": 1
}
```

#### Create Resource (Admin Only)
- **POST** `/resources`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Create a new resource (admin only)
- **Body**:
```json
{
    "name": "Meeting Room B",
    "description": "Small meeting room",
    "capacity": 8,
    "location": "Floor 2",
    "category_id": 1
}
```
- **Response**:
```json
{
    "id": 2,
    "name": "Meeting Room B",
    "description": "Small meeting room",
    "capacity": 8,
    "location": "Floor 2",
    "category_id": 1
}
```

#### Update Resource (Admin Only)
- **PUT** `/resources/{id}`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Update a resource (admin only)
- **Body**:
```json
{
    "name": "Updated Room Name",
    "capacity": 15
}
```
- **Response**:
```json
{
    "id": 1,
    "name": "Updated Room Name",
    "description": "Large conference room",
    "capacity": 15,
    "location": "Floor 1",
    "category_id": 1
}
```

#### Delete Resource (Admin Only)
- **DELETE** `/resources/{id}`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Delete a resource (admin only)
- **Response**:
```json
{
    "message": "Resource deleted"
}
```

### 3. Bookings

#### Get All Bookings
- **GET** `/bookings`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Get all bookings (with relationships)
- **Response**:
```json
[
    {
        "id": 1,
        "user_id": 1,
        "resource_id": 1,
        "booking_status_id": 2,
        "start_time": "2024-01-15T09:00:00.000000Z",
        "end_time": "2024-01-15T11:00:00.000000Z",
        "resource": {
            "id": 1,
            "name": "Conference Room A"
        },
        "user": {
            "id": 1,
            "name": "John Doe"
        },
        "status": {
            "id": 2,
            "name": "Approved"
        }
    }
]
```

#### Get Single Booking
- **GET** `/bookings/{id}`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Get a specific booking
- **Response**: Same as above but single object

#### Create Booking (User Only)
- **POST** `/bookings`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Create a new booking (user only)
- **Body**:
```json
{
    "resource_id": 1,
    "start_time": "2024-01-15T09:00:00",
    "end_time": "2024-01-15T11:00:00"
}
```
- **Response**:
```json
{
    "message": "Booking created"
}
```

#### Update Booking
- **PUT** `/bookings/{id}`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Update a booking
- **Body**:
```json
{
    "start_time": "2024-01-15T10:00:00",
    "end_time": "2024-01-15T12:00:00"
}
```
- **Response**:
```json
{
    "id": 1,
    "user_id": 1,
    "resource_id": 1,
    "booking_status_id": 1,
    "start_time": "2024-01-15T10:00:00.000000Z",
    "end_time": "2024-01-15T12:00:00.000000Z"
}
```

#### Delete Booking (User Only)
- **DELETE** `/bookings/{id}`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Delete a booking (user only)
- **Response**:
```json
{
    "message": "Booking deleted"
}
```

#### Get My Bookings (User Only)
- **GET** `/my-bookings`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Get current user's bookings
- **Response**: Array of bookings for the authenticated user

#### Get All Bookings (Admin Only)
- **GET** `/admin/bookings`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Get all bookings (admin only)
- **Response**: Array of all bookings with relationships

#### Approve Booking (Admin Only)
- **POST** `/bookings/{id}/approve`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Approve a booking (admin only)
- **Response**:
```json
{
    "message": "Booking approved"
}
```

#### Reject Booking (Admin Only)
- **POST** `/bookings/{id}/reject`
- **Headers**: `Authorization: Bearer {token}`
- **Description**: Reject a booking (admin only)
- **Response**:
```json
{
    "message": "Booking rejected"
}
```

## Testing in Postman

### Setup
1. Create a new Postman collection
2. Set up environment variables:
   - `base_url`: `http://localhost:8000/api`
   - `token`: (will be set after login)

### Testing Flow
1. **Register/Login**: Use `/register` or `/login` to get a token
2. **Set Token**: Copy the token from the response and set it as an environment variable
3. **Add Authorization**: In your requests, add header: `Authorization: Bearer {{token}}`

### Example Postman Collection
You can import this JSON into Postman:

```json
{
    "info": {
        "name": "Facility Booking API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "variable": [
        {
            "key": "base_url",
            "value": "http://localhost:8000/api"
        }
    ],
    "item": [
        {
            "name": "Auth",
            "item": [
                {
                    "name": "Register",
                    "request": {
                        "method": "POST",
                        "header": [],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"name\": \"Test User\",\n    \"email\": \"test@example.com\",\n    \"password\": \"password123\",\n    \"password_confirmation\": \"password123\"\n}",
                            "options": {
                                "raw": {
                                    "language": "json"
                                }
                            }
                        },
                        "url": {
                            "raw": "{{base_url}}/register",
                            "host": ["{{base_url}}"],
                            "path": ["register"]
                        }
                    }
                },
                {
                    "name": "Login",
                    "request": {
                        "method": "POST",
                        "header": [],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"email\": \"test@example.com\",\n    \"password\": \"password123\"\n}",
                            "options": {
                                "raw": {
                                    "language": "json"
                                }
                            }
                        },
                        "url": {
                            "raw": "{{base_url}}/login",
                            "host": ["{{base_url}}"],
                            "path": ["login"]
                        }
                    }
                }
            ]
        }
    ]
}
```

## Error Responses

### 401 Unauthorized
```json
{
    "error": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
    "error": "Unauthorized"
}
```

### 404 Not Found
```json
{
    "error": "Resource not found"
}
```

### 422 Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

## Status Codes
- **1**: Pending
- **2**: Approved
- **3**: Rejected 