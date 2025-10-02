<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExportDatabaseSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:export-schema 
                            {--output= : Output file path (default: database_schema.sql)}
                            {--include-data : Include sample data in the export}
                            {--tables= : Comma-separated list of specific tables to export}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export the complete database schema including tables, procedures, and structure';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $outputFile = $this->option('output') ?: 'database_schema.sql';
        $includeData = $this->option('include-data');
        $specificTables = $this->option('tables');

        $this->info('Exporting database schema...');

        try {
            $schema = $this->generateSchema($includeData, $specificTables);

            file_put_contents($outputFile, $schema);

            $this->info("Schema exported successfully to: {$outputFile}");
            $this->line("File size: " . $this->formatBytes(filesize($outputFile)));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to export schema: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Generate the complete database schema
     */
    private function generateSchema(bool $includeData = false, ?string $specificTables = null): string
    {
        $tables = $this->getTables($specificTables);
        $procedures = $this->getStoredProcedures();

        $schema = $this->getHeader();
        $schema .= $this->getTableDefinitions($tables);
        $schema .= $this->getStoredProcedureDefinitions($procedures);

        if ($includeData) {
            $schema .= $this->getSampleData($tables);
        }

        $schema .= $this->getFooter();

        return $schema;
    }

    /**
     * Get all database tables
     */
    private function getTables(?string $specificTables = null): array
    {
        if ($specificTables) {
            return explode(',', $specificTables);
        }

        // Use raw SQL query to get table names
        $tables = DB::select("SHOW TABLES");
        $tableKey = 'Tables_in_' . config('database.connections.' . config('database.default') . '.database');

        return array_map(function ($table) use ($tableKey) {
            return $table->$tableKey;
        }, $tables);
    }

    /**
     * Get stored procedures from the database
     */
    private function getStoredProcedures(): array
    {
        try {
            $procedures = DB::select("
                SELECT ROUTINE_NAME 
                FROM information_schema.ROUTINES 
                WHERE ROUTINE_TYPE = 'PROCEDURE' 
                AND ROUTINE_SCHEMA = DATABASE()
            ");

            return array_column($procedures, 'ROUTINE_NAME');
        } catch (\Exception $e) {
            $this->warn('Could not retrieve stored procedures: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get the schema header
     */
    private function getHeader(): string
    {
        $date = now()->format('Y-m-d H:i:s');

        return "-- =============================================
-- Laravel Veltro Application Database Schema
-- Generated: {$date}
-- Database: " . config('database.connections.' . config('database.default') . '.database') . "
-- =============================================

-- Set default charset and collation
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =============================================
-- Database Creation (if needed)
-- =============================================
-- CREATE DATABASE IF NOT EXISTS `" . config('database.connections.' . config('database.default') . '.database') . "` 
-- DEFAULT CHARACTER SET utf8mb4 
-- COLLATE utf8mb4_unicode_ci;
-- USE `" . config('database.connections.' . config('database.default') . '.database') . "`;

-- =============================================
-- Tables
-- =============================================

";
    }

    /**
     * Get table definitions
     */
    private function getTableDefinitions(array $tables): string
    {
        $definitions = '';

        foreach ($tables as $tableName) {
            // Skip system tables that we'll handle manually
            if (in_array($tableName, ['migrations'])) {
                continue;
            }

            try {
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
                $definitions .= "-- {$tableName} table\n";
                $definitions .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $definitions .= $createTable->{'Create Table'} . ";\n\n";
            } catch (\Exception $e) {
                $this->warn("Could not export table {$tableName}: " . $e->getMessage());
            }
        }

        // Always include migrations table manually
        $definitions .= "-- Migrations table (Laravel's migration tracking)\n";
        $definitions .= "DROP TABLE IF EXISTS `migrations`;\n";
        $definitions .= "CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        return $definitions;
    }

    /**
     * Get stored procedure definitions
     */
    private function getStoredProcedureDefinitions(array $procedures): string
    {
        if (empty($procedures)) {
            return '';
        }

        $definitions = "-- =============================================
-- Stored Procedures
-- =============================================

";

        foreach ($procedures as $procedure) {
            try {
                $createProcedure = DB::select("SHOW CREATE PROCEDURE `{$procedure}`")[0];
                $definitions .= "-- Drop existing procedure if it exists\n";
                $definitions .= "DROP PROCEDURE IF EXISTS `{$procedure}`;\n\n";
                $definitions .= $createProcedure->{'Create Procedure'} . "\n\n";
            } catch (\Exception $e) {
                $this->warn("Could not export procedure {$procedure}: " . $e->getMessage());
            }
        }

        return $definitions;
    }

    /**
     * Get sample data
     */
    private function getSampleData(array $tables): string
    {
        $data = "-- =============================================
-- Sample Data
-- =============================================

";

        // Insert sample migration records
        $data .= "-- Insert sample migration records to track current state\n";
        $data .= "INSERT INTO `migrations` (`migration`, `batch`) VALUES\n";
        $data .= "('0001_01_01_000000_create_users_table', 1),\n";
        $data .= "('0001_01_01_000001_create_cache_table', 1),\n";
        $data .= "('0001_01_01_000002_create_jobs_table', 1),\n";
        $data .= "('2025_08_18_002346_create_teams_table', 2),\n";
        $data .= "('2025_09_11_013741_create_team_user_table', 3),\n";
        $data .= "('2025_09_15_021323_create_auth_stored_procedures', 4),\n";
        $data .= "('2025_09_15_022113_create_application_logs_table', 5);\n\n";

        return $data;
    }

    /**
     * Get the schema footer
     */
    private function getFooter(): string
    {
        return "-- =============================================
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
";
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
