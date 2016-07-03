<?php

use HNG\Lunchbox;
use Illuminate\Database\Seeder;

class LunchboxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $boxes = [
            [
                'user_id' => 1,
                'buka_id' => 1,
                'created_at' => Carbon\Carbon::create(2016, 06, 1),
                'updated_at' => Carbon\Carbon::create(2016, 06, 1),
            ],
            [
                'user_id' => 1,
                'buka_id' => 1,
                'free_lunch' => true,
                'created_at' => Carbon\Carbon::create(2016, 05, 1),
                'updated_at' => Carbon\Carbon::create(2016, 05, 1),
            ],
            [
                'user_id' => 1,
                'buka_id' => 1,
                'created_at' => Carbon\Carbon::create(2016, 05, 2),
                'updated_at' => Carbon\Carbon::create(2016, 05, 2),
            ],
        ];

        foreach ($boxes as $box) Lunchbox::create($box);
    }
}
