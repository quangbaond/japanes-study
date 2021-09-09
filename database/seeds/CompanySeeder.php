<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company')->insert([
            [
                'name' => 'Company A',
                'created_at' => now()
            ],
            [
                'name' => 'Company B',
                'created_at' => now()
            ],
            [
                'name' => 'Company C',
                'created_at' => now()
            ],
            [
                'name' => 'Company D',
                'created_at' => now()
            ],
            [
                'name' => 'Company E',
                'created_at' => now()
            ]
        ]);
    }
}
