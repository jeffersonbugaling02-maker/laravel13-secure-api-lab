# Laravel 13 Role-Based Access Control (RBAC)

**Course:** Integrative Programming & Technologies II  
**Module:** 5 — Lesson 2: Authorization and Role-Based Access Control  
**Framework:** Laravel 13  
**Database:** MySQL  
**Authentication:** Laravel Sanctum  
**API Testing:** Postman

## 1. Project Overview

This project implements Role-Based Access Control (RBAC) in a Laravel 13 REST API. It demonstrates how to authenticate users and restrict access to API endpoints based on their assigned roles.

The system has two user roles:

- **Admin** – Can access shared resources and the Admin Dashboard.
- **Student** – Can access shared resources and the Student Dashboard.

The system uses Laravel Sanctum for token-based authentication and custom middleware for authorization.

## 2. Learning Objectives

- Implement role-based access control in Laravel 13.
- Add a role column to the users database table.
- Create Admin and Student accounts.
- Protect API routes using middleware.
- Apply token-based authentication using Laravel Sanctum.
- Test authorized and unauthorized API requests using Postman.

## 3. Authentication vs. Authorization

**Authentication** verifies the identity of a user. In this project, users authenticate by providing their email and password through the login endpoint. Laravel Sanctum generates an access token for successful login.

**Authorization** determines what an authenticated user is allowed to access. The custom middleware checks the user's role before allowing access to protected endpoints.

### Difference Between HTTP 401 and HTTP 403

- **401 Unauthorized:** The request does not have valid authentication credentials. For example, a user tries to access the Admin Dashboard without a token.
- **403 Forbidden:** The user is authenticated but does not have the required role. For example, a Student tries to access the Admin Dashboard.

## 4. User Roles and Permissions

| Feature | Admin | Student |
|---|---|---|
| Login | Yes | Yes |
| View Profile | Yes | Yes |
| View Students | Yes | Yes |
| Admin Dashboard | Yes | No |
| Student Dashboard | No | Yes |
| Logout | Yes | Yes |

Newly registered users receive the default `student` role. Public registration does not allow users to assign themselves an Admin role.

## 5. Middleware Implementation

Two custom middleware classes are used:

### CheckRole Middleware

File: `app/Http/Middleware/CheckRole.php`

- Checks the authenticated user's role.
- Supports one or more allowed roles.
- Returns HTTP 401 if the user is not authenticated.
- Returns HTTP 403 if the user's role is not allowed.
- Allows the request to continue if the role is permitted.

### AdminMiddleware

File: `app/Http/Middleware/AdminMiddleware.php`

- Allows access only to users with the `admin` role.
- Returns HTTP 401 for unauthenticated requests.
- Returns HTTP 403 for authenticated users without the Admin role.

### Middleware Aliases

The middleware aliases are registered in `bootstrap/app.php`:

- `role` – CheckRole middleware.
- `admin` – AdminMiddleware.

## 6. API Endpoints

Base URL: `http://127.0.0.1:8000`

| Method | Endpoint | Access |
|---|---|---|
| GET | `/api/hello` | Public |
| POST | `/api/register` | Public |
| POST | `/api/login` | Public |
| GET | `/api/profile` | Authenticated users |
| POST | `/api/logout` | Authenticated users |
| GET | `/api/students` | Admin and Student |
| GET | `/api/admin/dashboard` | Admin only |
| GET | `/api/student/dashboard` | Student only |

Protected endpoints require a valid Laravel Sanctum Bearer token.

## 7. Installation and Setup

### Requirements

- PHP 8.2 or later, compatible with the installed Laravel 13 dependencies.
- Composer
- MySQL
- Laravel 13
- Postman

### Setup Instructions

1. Clone the repository:

   ```bash
   git clone YOUR_GITHUB_REPOSITORY_URL
   ```

2. Open the project directory:

   ```bash
   cd YOUR_PROJECT_FOLDER
   ```

3. Install dependencies:

   ```bash
   composer install
   ```

4. Create your environment file:

   ```bash
   copy .env.example .env
   ```

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Configure your MySQL database credentials in `.env`.

7. Run the database migrations:

   ```bash
   php artisan migrate
   ```

   Do not use `php artisan migrate:fresh` on a database containing existing data.

8. Create an Admin and Student account using the Laravel Tinker console. Use your own secure passwords:

   ```bash
   php artisan tinker
   ```

   Example Admin account:

   ```php
   \App\Models\User::create([
       'name' => 'Admin',
       'email' => 'admin@example.com',
       'password' => \Illuminate\Support\Facades\Hash::make('YOUR_ADMIN_PASSWORD'),
       'role' => 'admin'
   ]);
   ```

   Example Student account:

   ```php
   \App\Models\User::create([
       'name' => 'Student',
       'email' => 'student@example.com',
       'password' => \Illuminate\Support\Facades\Hash::make('YOUR_STUDENT_PASSWORD'),
       'role' => 'student'
   ]);
   ```

   Exit Tinker:

   ```php
   exit
   ```

9. Clear Laravel caches:

   ```bash
   php artisan optimize:clear
   ```

10. Start the development server:

    ```bash
    php artisan serve
    ```

11. Open Postman and test the API endpoints.

## 8. Postman Testing Results

All eight required RBAC test cases were tested using Postman.

| No. | Test Scenario | Expected Status | Result |
|---|---|---|---|
| 1 | Admin Login | 200 OK | Passed |
| 2 | Admin accesses Admin Dashboard | 200 OK | Passed |
| 3 | Admin accesses Student Dashboard | 403 Forbidden | Passed |
| 4 | Student Login | 200 OK | Passed |
| 5 | Student accesses Student Dashboard | 200 OK | Passed |
| 6 | Student accesses Admin Dashboard | 403 Forbidden | Passed |
| 7 | Student accesses shared Students route | 200 OK | Passed |
| 8 | Access Admin Dashboard without token | 401 Unauthorized | Passed |

### Explanation of Test Results

The Admin account successfully accessed the Admin Dashboard but was denied access to the Student Dashboard.

The Student account successfully accessed the Student Dashboard and shared Students endpoint but was denied access to the Admin Dashboard.

Requests without authentication credentials received HTTP 401 Unauthorized.

These results demonstrate that authentication and role-based authorization are working as intended.

## 9. Screenshots

The `screenshots` folder contains the Postman evidence for all eight test cases.

| Screenshot | Description |
|---|---|
| `01-admin-login.png` | Admin login successful |
| `02-admin-allowed.png` | Admin Dashboard access allowed |
| `03-admin-denied.png` | Admin denied Student Dashboard access |
| `04-student-login.png` | Student login successful |
| `05-student-allowed.png` | Student Dashboard access allowed |
| `06-student-denied.png` | Student denied Admin Dashboard access |
| `07-shared-route.png` | Student accesses shared Students endpoint |
| `08-no-token.png` | Access denied without authentication token |

## 10. Reflection

Through this laboratory activity, I learned how to implement Role-Based Access Control in Laravel 13 using middleware and Laravel Sanctum.

I learned the difference between authentication and authorization. Authentication verifies the identity of a user, while authorization determines which resources the user can access.

I also learned how to create custom middleware, register middleware aliases, protect API routes, and test different user permissions using Postman.

This activity helped me understand the importance of API security and how role-based access control prevents users from accessing resources they are not authorized to use.

## 11. Security Notes

- The `.env` file must not be uploaded to GitHub.
- Never publish passwords, access tokens, or database credentials.
- Use `.env.example` as the environment configuration template.
- Public registration must not allow users to assign themselves an Admin role.
- Use secure passwords for all accounts.

## 12. Technologies Used

- Laravel 13
- PHP
- MySQL
- Laravel Sanctum
- Postman
- Git and GitHub

---

**Project:** Role-Based Access Control (RBAC) with Laravel 13  
**Course:** Integrative Programming & Technologies II