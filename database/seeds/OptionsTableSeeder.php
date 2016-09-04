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
            'CURRENCY'        => '₦',
            'FREELUNCH_QUOTA' => 100,
            'PERMISSIONS'     => \HNG\Providers\AuthServiceProvider::PERMISSIONS,
            'ALLOW_ANYTIME_FOOD_ORDERS' => 'false',
            'WORK_RESUMES'    => 8,
            'WORK_CLOSES'     => 6,
            'ORDER_RESUMES'   => 6,
            'ORDER_CLOSES'    => 9,
        ];

        foreach ($options as $name => $value) {
            add_option($name, $value);
        }
    }
}
