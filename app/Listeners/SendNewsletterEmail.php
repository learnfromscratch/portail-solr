<?php

namespace App\Listeners;

use App\Events\Newsletter;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\Newsletter;

class SendNewsletterEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Newsletter  $event
     * @return void
     */
    public function handle(Newsletter $event)
    {
        Mail::to($event->user)->send(new Newsletter());
    }
}
