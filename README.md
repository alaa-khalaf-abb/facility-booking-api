# Facility Booking API

A Laravel 12 REST API for managing facility resources (rooms/equipment/vehicles) and booking workflows with role-based access.

## What this project does

- User registration and login with Laravel Sanctum tokens
- Role-based authorization (`admin`, `user`)
- Resource management (CRUD)
- Booking management with approval/rejection workflow
- Overlap protection for approved bookings on the same resource
- Seeded baseline data for statuses, categories, and demo users

## Tech stack

- PHP 8.2+
- Laravel 12
- Laravel Sanctum (token auth)
- MySQL/SQLite/PostgreSQL via Laravel DB config

## Core domain model

- **User**: has a role (`admin` or `user`)
- **Resource**: bookable item with optional category
- **Category**: resource grouping (`Room`, `Equipment`, `Vehicle` seeded)
- **Booking**: user request for a resource and time range
- **BookingStatus**: `pending`, `approved`, `rejected`

## Roles and permissions

### User role
- Create bookings
- Delete bookings (user routes)
- View own bookings (`/api/my-bookings`)
- Read resources

### Admin role
- Full resource CRUD
- Approve/reject bookings
- View all bookings (`/api/admin/bookings`)

## Authentication flow

1. Register or login via `/api/register` or `/api/login`
2. Copy returned bearer token
3. Call protected endpoints with:

```http
Authorization: ******
Accept: application/json
```

## API overview

### Public endpoints

- `POST /api/register`
- `POST /api/login`

### Authenticated endpoints

- `POST /api/logout`
- `GET /api/user`
- `GET /api/resources`
- `GET /api/resources/{id}`
- `GET /api/bookings`
- `GET /api/bookings/{id}`
- `PUT /api/bookings/{id}`

### User-only endpoints

- `POST /api/bookings`
- `DELETE /api/bookings/{id}`
- `GET /api/my-bookings`

### Admin-only endpoints

- `POST /api/resources`
- `PUT /api/resources/{id}`
- `DELETE /api/resources/{id}`
- `POST /api/bookings/{id}/approve`
- `POST /api/bookings/{id}/reject`
- `GET /api/admin/bookings`

## Booking overlap behavior

The API prevents conflicting **approved** bookings for the same resource and overlapping time window.

- Conflict returns **HTTP 409**
- Applies during booking create/update checks and approval flow

## Quick start

1. Install dependencies:
   ```bash
   composer install
   ```
2. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Configure database in `.env`
4. Run migrations + seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Start server:
   ```bash
   php artisan serve
   ```

API will be available at `http://127.0.0.1:8000`.

## Seeded demo accounts

After `php artisan migrate --seed`:

- **Admin**: `admin@example.com` / `password`
- **User**: `user@example.com` / `password`

## Testing and quality checks

- Run tests:
  ```bash
  composer test
  ```
- Run formatter/lint checks:
  ```bash
  ./vendor/bin/pint --test
  ```

## Postman

Ready-to-use Postman files are in:

- `/postman/Facility Booking API.postman_collection.json`
- `/postman/AdminEnv.postman_environment.json`
- `/postman/UserEnv.postman_environment.json`
