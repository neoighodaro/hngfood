<?php

use HNG\Lunch;
use Illuminate\Database\Seeder;

class LunchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lunches = [
            // White house
            ['name' => 'Jollof Rice', 'cost' => 0.00, 'buka_id' => 1],
            ['name' => 'Fried Rice', 'cost' => 0.00, 'buka_id' => 1],
            ['name' => 'Beans', 'cost' => 0.00, 'buka_id' => 1],
            ['name' => 'Plantain', 'cost' => 0.00, 'buka_id' => 1],
            ['name' => 'Amala', 'cost' => 0.00, 'buka_id' => 1],
            ['name' => 'Chicken', 'cost' => 100.00, 'buka_id' => 1],
            ['name' => 'Beef', 'cost' => 50.00, 'buka_id' => 1],

            // Commint
            ['name' => 'Jollof Rice', 'cost' => 150.00, 'buka_id' => 2],
            ['name' => 'Fried Rice', 'cost' => 150.00, 'buka_id' => 2],
            ['name' => 'Yam Pottage', 'cost' => 250.00, 'buka_id' => 2],
            ['name' => 'Beans', 'cost' => 150.00, 'buka_id' => 2],
            ['name' => 'Plantain', 'cost' => 100.00, 'buka_id' => 2],
            ['name' => 'Chicken', 'cost' => 500.00, 'buka_id' => 2],
            ['name' => 'Beef', 'cost' => 100.00, 'buka_id' => 2],
            ['name' => 'Goat Meat', 'cost' => 900.00, 'buka_id' => 2],
            ['name' => 'Amala', 'cost' => 150.00, 'buka_id' => 2],
        ];

        foreach ($lunches as $lunch) {
            Lunch::create($lunch);
        }
    }
}
