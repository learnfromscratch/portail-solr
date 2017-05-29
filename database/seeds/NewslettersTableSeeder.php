<?php

use Illuminate\Database\Seeder;

class NewslettersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('newsletters')->insert(['user_id' => 1]);
        DB::table('newsletters')->insert(['user_id' => 2]);
    }
}
