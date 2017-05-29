<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\Mail\NewsletterEmail;
use Illuminate\Support\Facades\DB;

class SendNewsletters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:newsletter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send newsletter to susbcribed users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = DB::table('users')->join('newsletters', 'users.id', '=', 'newsletters.user_id')->get();
        Mail::to($users)->send(new NewsletterEmail());
        $this->info('newsletters sended successfully');
    }
}
