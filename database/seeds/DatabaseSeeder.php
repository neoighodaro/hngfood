<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(BukasTableSeeder::class);
        $this->call(LunchboxesTableSeeder::class);
        $this->call(LunchTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(FreelunchTableSeeder::class);
        $this->call(OptionsTableSeeder::class);
    }
}
