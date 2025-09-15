# Authentication Stored Procedures

This document describes the stored procedures created for user authentication in the Laravel application.

## Stored Procedures Created

### 1. `sp_register_user`

**Purpose**: Registers a new user in the system
**Parameters**:

-   `p_name` (IN): User's full name
-   `p_email` (IN): User's email address
-   `p_password` (IN): Hashed password
-   `p_user_id` (OUT): ID of the created user
-   `p_success` (OUT): Boolean indicating success/failure
-   `p_message` (OUT): Success or error message

**Usage**:

```sql
CALL sp_register_user('John Doe', 'john@example.com', '$2y$10$...', @user_id, @success, @message);
SELECT @user_id, @success, @message;
```

### 2. `sp_check_email_unique`

**Purpose**: Checks if an email address is already registered
**Parameters**:

-   `p_email` (IN): Email address to check
-   `p_is_unique` (OUT): Boolean indicating if email is unique

**Usage**:

```sql
CALL sp_check_email_unique('john@example.com', @is_unique);
SELECT @is_unique;
```

### 3. `sp_get_user_by_email`

**Purpose**: Retrieves user data by email address
**Parameters**:

-   `p_email` (IN): Email address to search for
-   `p_user_id` (OUT): User ID
-   `p_user_name` (OUT): User's name
-   `p_user_email` (OUT): User's email
-   `p_user_password` (OUT): User's hashed password
-   `p_user_exists` (OUT): Boolean indicating if user exists

**Usage**:

```sql
CALL sp_get_user_by_email('john@example.com', @user_id, @user_name, @user_email, @user_password, @user_exists);
SELECT @user_id, @user_name, @user_email, @user_password, @user_exists;
```

### 4. `sp_authenticate_user`

**Purpose**: Authenticates a user (Note: Password verification is handled in Laravel)
**Parameters**:

-   `p_email` (IN): Email address
-   `p_password` (IN): Plain text password
-   `p_user_id` (OUT): User ID if found
-   `p_user_name` (OUT): User's name
-   `p_user_email` (OUT): User's email
-   `p_success` (OUT): Boolean indicating success
-   `p_message` (OUT): Success or error message

**Usage**:

```sql
CALL sp_authenticate_user('john@example.com', 'plaintext_password', @user_id, @user_name, @user_email, @success, @message);
SELECT @user_id, @user_name, @user_email, @success, @message;
```

## Laravel Integration

The stored procedures are integrated into the Laravel application through:

1. **AuthService**: A service class that handles all stored procedure calls
2. **AuthController**: Updated to use the AuthService instead of direct Eloquent operations
3. **Migration**: Creates all stored procedures in the database

## Installation

To install the stored procedures, run:

```bash
php artisan migrate
```

This will execute the migration file that creates all the stored procedures.

## Security Notes

-   All passwords are hashed using Laravel's `Hash::make()` before being passed to stored procedures
-   Password verification is handled in Laravel using `Hash::check()`
-   All stored procedures use transactions for data consistency
-   Error handling is implemented in each stored procedure

## Benefits

1. **Performance**: Database-level operations are faster than application-level operations
2. **Consistency**: Business logic is centralized in the database
3. **Security**: Reduced SQL injection risks with parameterized procedures
4. **Maintainability**: Authentication logic is separated into dedicated service classes
