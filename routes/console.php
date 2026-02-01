<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('send-mail', function () {
    Mail::raw('Congrats for sending test email with Mailtrap!', function ($message) {
        $message->from(config('mail.from.address'), config('mail.from.name'))
            ->to('mohammed.mahran@atamds.com')
            ->subject('You are awesome!');
    });

    $this->info('Mail sent successfully');
})->purpose('Send test mail via Laravel Mail (Mailtrap SMTP)');
