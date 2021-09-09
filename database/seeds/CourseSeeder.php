<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course')->insert([
            [
                'course_code' => 'code_N5',
                'description' => 'Japanese N5',
                'name'=> 'Minna no nihongo N5',
                'level_id'=> '5',
                'photo'=> 'https://japanese-study.s3-ap-southeast-1.amazonaws.com/file/N5.jpeg',
            ],
            [
                'course_code' => 'code_N4',
                'description' => 'Japanese N4',
                'name'=> 'Minna no nihongo N4',
                'level_id'=> '4',
                'photo'=> 'https://japanese-study.s3-ap-southeast-1.amazonaws.com/file/N4.jpeg',
            ],
            [
                'course_code' => 'code_N3',
                'description' => 'Japanese N3',
                'name'=> 'Soumatome N3',
                'level_id'=> '3',
                'photo'=> 'https://japanese-study.s3-ap-southeast-1.amazonaws.com/file/N3.jpeg',
            ],
            [
                'course_code' => 'code_N2',
                'description' => 'Japanese N2',
                'name'=> 'Soumatome N2',
                'level_id'=> '2',
                'photo'=> 'https://japanese-study.s3-ap-southeast-1.amazonaws.com/file/N2.jpeg',
            ],
            [
                'course_code' => 'code_N1',
                'description' => 'Japanese N1',
                'name'=> 'Shinkanzen master N1',
                'level_id'=> '1',
                'photo'=> 'https://japanese-study.s3-ap-southeast-1.amazonaws.com/file/N1.jpeg',
            ]
        ]);
    }
}
