<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherCoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teacher_coin')->insert([
            [
                'teacher_id' => 2,
                'coin' => 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 3,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 4,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 5,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 6,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 7,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 8,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 9,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 10,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 11,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 12,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 26,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 27,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 28,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 29,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 30,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 36,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 37,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 38,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 39,
                'coin'=> 100,
                'created_at'=> now()
            ],
            [
                'teacher_id' => 40,
                'coin'=> 100,
                'created_at'=> now()
            ],
        ]);
    }
}
