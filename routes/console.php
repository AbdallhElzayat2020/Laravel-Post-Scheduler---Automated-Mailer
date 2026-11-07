<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:write-log')
    ->timezone('africa/cairo')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground()
    ->onOneServer();

Schedule::command('app:publish-posts')
    ->timezone('africa/cairo')
    ->weekly()
    ->withoutOverlapping()
    ->runInBackground()
    ->onOneServer();
