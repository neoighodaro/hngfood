<?php

use HNG\Freelunch;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FreelunchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $freelunches = [
            ['reason' => 'Because I can.', 'from_id' => 2, 'to_id' => 1, 'expires_at' => Carbon::tomorrow()],
            ['reason' => 'Because I can.', 'from_id' => 3, 'to_id' => 1, 'expires_at' => Carbon::tomorrow()],
            ['reason' => 'Because I can.', 'from_id' => 2, 'to_id' => 1, 'expires_at' => Carbon::tomorrow()],
            ['reason' => 'Because I can.', 'from_id' => 3, 'to_id' => 1, 'expires_at' => Carbon::tomorrow()],
            ['reason' => 'Because I can.', 'from_id' => 2, 'to_id' => 1, 'expires_at' => Carbon::tomorrow()],
            ['reason' => 'Because I can.', 'from_id' => 3, 'to_id' => 1, 'expires_at' => Carbon::tomorrow()],
        ];

        foreach ($freelunches as $freelunch) {
            Freelunch::create($freelunch);
        }
    }
}
