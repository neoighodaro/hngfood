<?php

use HNG\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // If there is a slack user for seeding defined...
        if ( ! env('USER_SLACK_ID')) {
            return;
        }

        // Create a sample user
        User::create([
            'slack_id' => env('USER_SLACK_ID'),
            'email'    => env('USER_SLACK_EMAIL'),
            'name'     => env('USER_SLACK_NAME'),
            'avatar'   => env('USER_SLACK_AVATAR'),
            'wallet'   => 5000.00,
        ]);
    }
}
