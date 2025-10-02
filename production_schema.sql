-- =============================================
-- Laravel Veltro Application Database Schema
-- Generated: 2025-09-17 22:26:18
-- Database: veltro
-- =============================================

-- Set default charset and collation
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =============================================
-- Database Creation (if needed)
-- =============================================
-- CREATE DATABASE IF NOT EXISTS `veltro` 
-- DEFAULT CHARACTER SET utf8mb4 
-- COLLATE utf8mb4_unicode_ci;
-- USE `veltro`;

-- =============================================
-- Tables
-- =============================================

-- cache table
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- cache_locks table
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- failed_jobs table
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- job_batches table
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- jobs table
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- password_reset_tokens table
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- sessions table
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- team_user table
DROP TABLE IF EXISTS `team_user`;
CREATE TABLE `team_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `joined_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_user_team_id_user_id_unique` (`team_id`,`user_id`),
  KEY `team_user_user_id_foreign` (`user_id`),
  KEY `team_user_team_id_role_index` (`team_id`,`role`),
  CONSTRAINT `team_user_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- teams table
DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_id` bigint unsigned NOT NULL,
  `max_members` int NOT NULL DEFAULT '11',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_owner_id_is_active_index` (`owner_id`,`is_active`),
  CONSTRAINT `teams_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- users table
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrations table (Laravel's migration tracking)
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Stored Procedures
-- =============================================

-- Drop existing procedure if it exists
DROP PROCEDURE IF EXISTS `sp_authenticate_user`;

CREATE DEFINER=`root`@`%` PROCEDURE `sp_authenticate_user`(
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

-- Drop existing procedure if it exists
DROP PROCEDURE IF EXISTS `sp_check_email_unique`;

CREATE DEFINER=`root`@`%` PROCEDURE `sp_check_email_unique`(
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

-- Drop existing procedure if it exists
DROP PROCEDURE IF EXISTS `sp_get_user_by_email`;

CREATE DEFINER=`root`@`%` PROCEDURE `sp_get_user_by_email`(
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

-- Drop existing procedure if it exists
DROP PROCEDURE IF EXISTS `sp_register_user`;

CREATE DEFINER=`root`@`%` PROCEDURE `sp_register_user`(
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

-- =============================================
-- Final Configuration
-- =============================================

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- Schema Export Complete
-- =============================================
-- This schema includes:
-- - All Laravel framework tables (users, sessions, cache, jobs, etc.)
-- - Application-specific tables (teams, team_user, application_logs)
-- - Authentication stored procedures
-- - Proper foreign key relationships
-- - Indexes for performance optimization
-- - Default charset and collation settings
-- =============================================
