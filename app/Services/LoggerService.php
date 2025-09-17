<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoggerService
{
    private static ?self $instance = null;

    private function __construct() {}

    /**
     * Get singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    /**
     * Log authentication events
     */
    public function logAuthEvent(string $event, array $data = []): void
    {
        $logData = [
            'event' => $event,
            'data' => $data,
            'timestamp' => now(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        // Log to file
        Log::channel('auth')->info('Authentication Event', $logData);

        // Log to database
        $this->logToDatabase('auth', $event, $logData);
    }

    /**
     * Log team events
     */
    public function logTeamEvent(string $event, array $data = []): void
    {
        $logData = [
            'event' => $event,
            'data' => $data,
            'timestamp' => now(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        // Log to file
        Log::channel('teams')->info('Team Event', $logData);

        // Log to database
        $this->logToDatabase('teams', $event, $logData);
    }

    /**
     * Log general application events
     */
    public function logApplicationEvent(string $level, string $message, array $context = []): void
    {
        $logData = array_merge($context, [
            'timestamp' => now(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Log to file
        Log::channel('application')->{$level}($message, $logData);

        // Log to database
        $this->logToDatabase('application', $message, $logData);
    }

    /**
     * Log to database
     */
    private function logToDatabase(string $category, string $event, array $data): void
    {
        try {
            DB::table('application_logs')->insert([
                'category' => $category,
                'event' => $event,
                'data' => json_encode($data),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Fallback to file logging if database logging fails
            Log::error('Failed to log to database: ' . $e->getMessage());
        }
    }

    /**
     * Get logs from database
     */
    public function getLogs(string $category = null, int $limit = 100): array
    {
        $query = DB::table('application_logs')
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($category) {
            $query->where('category', $category);
        }

        return $query->get()->toArray();
    }
}
