<?php

use HNG\Socialite\SlackProvider;
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

        // Create sample users

        User::create([
            'slack_id' => env('USER_SLACK_ID'),
            'username' => 'neo',
            'email'    => env('USER_SLACK_EMAIL'),
            'password' => bcrypt('samplepassword'),
            'role'     => (new User)->getRoleIdFromName('Super Admin'),
            'name'     => env('USER_SLACK_NAME'),
            'avatar'   => env('USER_SLACK_AVATAR'),
            'wallet'   => 1000.00,
        ]);

       User::create([
           'slack_id' => str_random(),
           'email'    => "dev@hng.tech",
           'name'     => "dev",
           'avatar'   => env('USER_SLACK_AVATAR'),
           'wallet'   => 0.00,
       ]);

       User::create([
           'slack_id' => str_random(),
           'email'    => 'dev2@hng.tech',
           'name'     => "dev2",
           'avatar'   => env('USER_SLACK_AVATAR'),
           'wallet'   => 0.00,
       ]);
    }
}
