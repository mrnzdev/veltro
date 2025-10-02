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
        // Create stored procedure for user registration
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
                
                -- Check if email already exists
                IF EXISTS (SELECT 1 FROM users WHERE email = p_email) THEN
                    SET p_success = FALSE;
                    SET p_message = 'Email already exists';
                    SET p_user_id = 0;
                ELSE
                    -- Insert new user
                    INSERT INTO users (name, email, password, created_at, updated_at)
                    VALUES (p_name, p_email, p_password, NOW(), NOW());
                    
                    SET p_user_id = LAST_INSERT_ID();
                    SET p_success = TRUE;
                    SET p_message = 'User registered successfully';
                END IF;
                
                COMMIT;
            END
        ");

        // Create stored procedure for user login
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
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    SET p_success = FALSE;
                    SET p_message = 'Error occurred during authentication';
                    SET p_user_id = 0;
                    SET p_user_name = '';
                    SET p_user_email = '';
                END;
                
                START TRANSACTION;
                
                -- Check if user exists and password matches
                SELECT id, name, email, password
                INTO p_user_id, p_user_name, p_user_email, @stored_password
                FROM users
                WHERE email = p_email
                LIMIT 1;
                
                -- Check if user was found and password matches
                IF p_user_id IS NULL THEN
                    SET p_success = FALSE;
                    SET p_message = 'Invalid credentials';
                    SET p_user_id = 0;
                    SET p_user_name = '';
                    SET p_user_email = '';
                ELSE
                    -- Note: Password verification should be done in Laravel
                    -- This SP just returns user data if email exists
                    SET p_success = TRUE;
                    SET p_message = 'User found';
                END IF;
                
                COMMIT;
            END
        ");

        // Create stored procedure for getting user by email
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
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    SET p_user_exists = FALSE;
                    SET p_user_id = 0;
                    SET p_user_name = '';
                    SET p_user_email = '';
                    SET p_user_password = '';
                END;
                
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

        // Create stored procedure for checking email uniqueness
        DB::unprepared("
            CREATE PROCEDURE sp_check_email_unique(
                IN p_email VARCHAR(255),
                OUT p_is_unique BOOLEAN
            )
            BEGIN
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    SET p_is_unique = FALSE;
                END;
                
                IF EXISTS (SELECT 1 FROM users WHERE email = p_email) THEN
                    SET p_is_unique = FALSE;
                ELSE
                    SET p_is_unique = TRUE;
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_register_user');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_authenticate_user');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_by_email');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_check_email_unique');
    }
};
