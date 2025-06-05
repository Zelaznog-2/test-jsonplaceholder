<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$schedule = app(Schedule::class);

# This command fetches user data from an external API and stores it in the database.
$schedule->command('app:fetch-user-data')
    ->everyMinute()
    ->onFailure(function () {
        Log::error('Failed to fetch user data from the external API.');
    })
    ->onSuccess(function () {
        Log::info('User data fetched successfully from the external API.');
    });

# This command fetches posts data from an external API and stores it in the database.
$schedule->command('app:fetch-posts-data')
    ->everyMinute()
    ->onFailure(function () {
        Log::error('Failed to fetch post data from the external API.');
    })
    ->onSuccess(function () {
        Log::info('Post data fetched successfully from the external API.');
    });

# This command fetches comments data from an external API and stores it in the database.
$schedule->command('app:fetch-comments-data')
    ->everyMinute()
    ->onFailure(function () {
        Log::error('Failed to fetch post data from the external API.');
    })
    ->onSuccess(function () {
        Log::info('Post data fetched successfully from the external API.');
    });



// Schedule the CommentsPerUserJob to run daily at midnigh

// Schedule the ClearCacheCommand to run every hour
$schedule->command('app:clear-cache-command')->hourly();