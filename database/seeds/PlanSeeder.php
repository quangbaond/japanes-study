<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->insert([
            [
                'name' => '1.500.000VND / 1 day',
                'product_id' => 'prod_IpuIj5E7WmITwm',
                'stripe_plan' => 'price_1IEEUGEJiJUZOnLWHccolmry',
                'cost' => 1500000,
                'interval' => 'day',
                'interval_count' => 1,
                'description' => '1.500.000VND / 1 day',
                'created_at' => now(),
            ],
            [
                'name' => '2.700.000VND / 3 day',
                'product_id' => 'prod_IpuLq2pmRjkHKY',
                'stripe_plan' => 'price_1IEEWYEJiJUZOnLW7IsBtL71',
                'cost' => 2700000,
                'interval' => 'day',
                'interval_count' => 3,
                'description' => '2.700.000VND / 3 day',
                'created_at' => now(),
            ],
            [
                'name' => '5.000.000VND / 6 day',
                'product_id' => 'prod_IpuMJW7I8gYGMS',
                'stripe_plan' => 'price_1IEEXYEJiJUZOnLW5ILvR4gc',
                'cost' => 5000000,
                'interval' => 'day',
                'interval_count' => 6,
                'description' => '5.000.000VND / 6 day',
                'created_at' => now(),
            ]
        ]);
    }
}
