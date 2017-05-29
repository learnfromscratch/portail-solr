<?php

namespace App\Listeners;

use App\Events\Alert;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAlert
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
     * @param  Alert  $event
     * @return void
     */
    public function handle(Alert $event)
    {
        //
    }
}
