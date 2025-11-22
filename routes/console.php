<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('users:send-inactive')
    ->dailyAt('08:00')
    ->withoutOverlapping() ->runInBackground() // Run in background to avoid blocking other scheduled tasks
    ->onSuccess(function () {
        // Optional: Log successful completion
    })
    ->onFailure(function () {
        // Optional: Send notification on failure
    });
