<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AbonnementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('abonnements')->insert(['user_id' => 1]);
        DB::table('abonnements')->insert(['user_id' => 2, 'start_date' => Carbon::now(), 'end_date' => Carbon::now()->addDay()]);
    }
}
