<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterCoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_coin')->insert([
            [
                'coin' => 500,
                'bonus_coin' => 0,
                'amount'=> 200000,
                'created_at'=> now(),
            ],
            [
                'coin' => 1000,
                'bonus_coin' => 100,
                'amount'=> 350000,
                'created_at'=> now(),
            ],
            [
                'coin' => 1500,
                'bonus_coin' => 150,
                'amount'=> 500000,
                'created_at'=> now(),
            ],
            [
                'coin' => 2000,
                'bonus_coin' => 200,
                'amount'=> 650000,
                'created_at'=> now(),
            ]
        ]);
    }
}
