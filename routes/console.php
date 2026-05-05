<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('oos:reminder')
    ->dailyAt('17:00')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();

Schedule::command('oos:missing-alert')
    ->dailyAt('17:30')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();

Schedule::command('daily:summary')
    ->dailyAt('18:00')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();
