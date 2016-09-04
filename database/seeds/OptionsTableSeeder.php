<?php

use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = [
            'APP_NAME'        => 'HNGFood',
            'SITE_LOGO'       => '/img/logo.svg',
            'SITE_FOOTER_TEXT' => 'Created by the HNG.tech team',
            'LANGUAGE'        => 'en',
            'CURRENCY'        => '₦',
            'FREELUNCH_QUOTA' => 100,
            'PERMISSIONS'     => \HNG\Providers\AuthServiceProvider::PERMISSIONS,
            'ALLOW_ANYTIME_FOOD_ORDERS' => 'true',
            'WORK_RESUMES'    => 8,
            'WORK_CLOSES'     => 6,
            'ORDER_RESUMES'   => 6,
            'ORDER_CLOSES'    => 9,
            'SLACK_CREDENTIALS' => [
                'client_id'     => env('SLACK_CLIENT_ID'),
                'domain'        => env('SLACK_TEAM_DOMAIN'),
                'client_secret' => env('SLACK_CLIENT_SECRET'),
                'redirect'      => env('SLACK_REDIRECT_CALLBACK_URL'),
            ],
            'SLACK_COMMAND_TOKENS'      => explode(',', env('SLACK_COMMAND_TOKENS')),
            'SLACK_DEFAULT_PERMISSIONS' => explode(',', env('SLACK_DEFAULT_PERMISSIONS')),
        ];

        foreach ($options as $name => $value) {
            add_option($name, $value);
        }
    }
}
