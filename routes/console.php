<?php

use App\Models\MatchRequest;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule: Auto-expire match requests
Schedule::call(function () {
    $expiredCount = MatchRequest::where('status', 'open')
        ->where('match_datetime', '<', now())
        ->update(['status' => 'expired']);

    if ($expiredCount > 0) {
        logger()->info("Expired {$expiredCount} match request(s)");
    }
})->hourly();
