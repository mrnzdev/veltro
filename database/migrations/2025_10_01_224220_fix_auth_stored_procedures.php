<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop existing procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_register_user');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_check_email_unique');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_by_email');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_authenticate_user');

        // Create improved stored procedure for user registration
        // Returns a result set instead of OUT parameters
        DB::unprepared("
            CREATE PROCEDURE sp_register_user(
                IN p_name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                IN p_email VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                IN p_password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            )
            BEGIN
                DECLARE v_user_id BIGINT DEFAULT 0;
                DECLARE v_success BOOLEAN DEFAULT FALSE;
                DECLARE v_message VARCHAR(255) DEFAULT '';
                
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    SELECT 0 as user_id, FALSE as success, 'Error occurred during registration' as message;
                END;
                
                START TRANSACTION;
                
                -- Check if email already exists
                IF EXISTS (SELECT 1 FROM users WHERE email = p_email) THEN
                    SELECT 0 as user_id, FALSE as success, 'Email already exists' as message;
                ELSE
                    -- Insert new user
                    INSERT INTO users (name, email, password, created_at, updated_at)
                    VALUES (p_name, p_email, p_password, NOW(), NOW());
                    
                    SET v_user_id = LAST_INSERT_ID();
                    
                    SELECT v_user_id as user_id, TRUE as success, 'User registered successfully' as message;
                END IF;
                
                COMMIT;
            END
        ");

        // Create improved stored procedure for checking email uniqueness
        // Returns a result set instead of OUT parameter
        DB::unprepared("
            CREATE PROCEDURE sp_check_email_unique(
                IN p_email VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            )
            BEGIN
                DECLARE v_is_unique BOOLEAN;
                
                IF EXISTS (SELECT 1 FROM users WHERE email = p_email) THEN
                    SELECT FALSE as is_unique;
                ELSE
                    SELECT TRUE as is_unique;
                END IF;
            END
        ");

        // Create improved stored procedure for getting user by email
        // Returns a result set instead of OUT parameters
        DB::unprepared("
            CREATE PROCEDURE sp_get_user_by_email(
                IN p_email VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            )
            BEGIN
                SELECT 
                    id as user_id,
                    name as user_name,
                    email as user_email,
                    password as user_password,
                    TRUE as user_exists
                FROM users
                WHERE email = p_email
                LIMIT 1;
            END
        ");

        // Create improved stored procedure for user authentication
        // Returns a result set instead of OUT parameters
        DB::unprepared("
            CREATE PROCEDURE sp_authenticate_user(
                IN p_email VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                IN p_password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            )
            BEGIN
                SELECT 
                    id as user_id,
                    name as user_name,
                    email as user_email,
                    password as user_password,
                    TRUE as success,
                    'User found' as message
                FROM users
                WHERE email = p_email
                LIMIT 1;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_register_user');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_check_email_unique');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_by_email');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_authenticate_user');

        // Recreate the old procedures with OUT parameters
        // (This is a simplified version - in production you might want to copy the exact original)
        DB::unprepared("
            CREATE PROCEDURE sp_register_user(
                IN p_name VARCHAR(255),
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255),
                OUT p_user_id BIGINT,
                OUT p_success BOOLEAN,
                OUT p_message VARCHAR(255)
            )
            BEGIN
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    SET p_success = FALSE;
                    SET p_message = 'Error occurred during registration';
                    SET p_user_id = 0;
                END;
                
                START TRANSACTION;
                
                IF EXISTS (SELECT 1 FROM users WHERE email = p_email) THEN
                    SET p_success = FALSE;
                    SET p_message = 'Email already exists';
                    SET p_user_id = 0;
                ELSE
                    INSERT INTO users (name, email, password, created_at, updated_at)
                    VALUES (p_name, p_email, p_password, NOW(), NOW());
                    
                    SET p_user_id = LAST_INSERT_ID();
                    SET p_success = TRUE;
                    SET p_message = 'User registered successfully';
                END IF;
                
                COMMIT;
            END
        ");

        DB::unprepared("
            CREATE PROCEDURE sp_check_email_unique(
                IN p_email VARCHAR(255),
                OUT p_is_unique BOOLEAN
            )
            BEGIN
                IF EXISTS (SELECT 1 FROM users WHERE email = p_email) THEN
                    SET p_is_unique = FALSE;
                ELSE
                    SET p_is_unique = TRUE;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE PROCEDURE sp_get_user_by_email(
                IN p_email VARCHAR(255),
                OUT p_user_id BIGINT,
                OUT p_user_name VARCHAR(255),
                OUT p_user_email VARCHAR(255),
                OUT p_user_password VARCHAR(255),
                OUT p_user_exists BOOLEAN
            )
            BEGIN
                SELECT id, name, email, password
                INTO p_user_id, p_user_name, p_user_email, p_user_password
                FROM users
                WHERE email = p_email
                LIMIT 1;
                
                IF p_user_id IS NULL THEN
                    SET p_user_exists = FALSE;
                    SET p_user_id = 0;
                    SET p_user_name = '';
                    SET p_user_email = '';
                    SET p_user_password = '';
                ELSE
                    SET p_user_exists = TRUE;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE PROCEDURE sp_authenticate_user(
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255),
                OUT p_user_id BIGINT,
                OUT p_user_name VARCHAR(255),
                OUT p_user_email VARCHAR(255),
                OUT p_success BOOLEAN,
                OUT p_message VARCHAR(255)
            )
            BEGIN
                SELECT id, name, email, password
                INTO p_user_id, p_user_name, p_user_email, @stored_password
                FROM users
                WHERE email = p_email
                LIMIT 1;
                
                IF p_user_id IS NULL THEN
                    SET p_success = FALSE;
                    SET p_message = 'Invalid credentials';
                    SET p_user_id = 0;
                    SET p_user_name = '';
                    SET p_user_email = '';
                ELSE
                    SET p_success = TRUE;
                    SET p_message = 'User found';
                END IF;
            END
        ");
    }
};
