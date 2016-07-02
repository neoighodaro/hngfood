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
            ['user_id' => 1, 'buka_id' => 1],
            ['user_id' => 2, 'buka_id' => 2, 'free_lunch' => true],
            ['user_id' => 3, 'buka_id' => 3],
        ];

        foreach ($boxes as $box) Lunchbox::create($box);
    }
}
