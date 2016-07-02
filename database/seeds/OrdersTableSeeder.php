<?php

use HNG\Order;
use HNG\Lunchbox;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders1 = [
            new Order(['name' => 'Beans', 'expected_cost' => 50.00]),
            new Order(['name' => 'Beef',  'expected_cost' => 150.00]),
            new Order(['name' => 'Rice',  'expected_cost' => 100.00, 'note' => 'Jollof or Fried.']),
        ];

        $orders2 = [
            new Order(['name' => 'Plantain', 'expected_cost' => 50.00]),
            new Order(['name' => 'Beef',  'expected_cost' => 150.00]),
            new Order(['name' => 'Rice',  'expected_cost' => 100.00, 'note' => 'Jollof or Fried.']),
        ];

        $orders3 = [
            new Order(['name' => 'Beef',  'expected_cost' => 150.00]),
            new Order(['name' => 'Rice',  'expected_cost' => 200.00, 'note' => 'Jollof or Fried.']),
        ];

        Lunchbox::find(1)->orders()->saveMany($orders1);
        Lunchbox::find(2)->orders()->saveMany($orders2);
        Lunchbox::find(3)->orders()->saveMany($orders3);
    }
}
