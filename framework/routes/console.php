<?php

use App\Console\Commands\CheckDatesCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(CheckDatesCommand::class, [env('FILE_PATH'), env('EMAIL')])->dailyAt(env('CHECK_TIME'));
