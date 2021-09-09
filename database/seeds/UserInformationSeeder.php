<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class UserInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $nationally = array_keys(config('nation'));
        DB::table('user_information')->insert([
            [
                'user_id'           => 2,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 3,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 4,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 5,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 6,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 7,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 8,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 9,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 10,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 11,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 12,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 13,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 14,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 15,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 16,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 17,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 18,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 19,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 20,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 21,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 22,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 23,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 24,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 25,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 26,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 27,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 28,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 29,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 30,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],

            [
                'user_id'           => 31,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 32,
                'membership_status' => 2,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 33,
                'membership_status' => 3,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 34,
                'membership_status' => 4,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 35,
                'membership_status' => 5,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 36,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 37,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 38,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 39,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 40,
                'membership_status' => null,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 41,
                'membership_status' => 1,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 42,
                'membership_status' => 2,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 43,
                'membership_status' => 3,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 44,
                'membership_status' => 4,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ],
            [
                'user_id'           => 45,
                'membership_status' => 5,
                'nationality'       => $nationally[rand(0,229)],
                'created_at'        => now()
            ]
        ]);
    }
}
