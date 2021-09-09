<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'nickname' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 1,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 2,
                'nickname' => 'teacher',
                'email' => 'teacher@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 3,
                'nickname' => 'teacher1',
                'email' => 'teacher1@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 4,
                'nickname' => 'teacher3',
                'email' => 'teacher3@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 5,
                'nickname' => 'teacher4',
                'email' => 'teacher4@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 6,
                'nickname' => 'teacher5',
                'email' => 'teacher5@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 7,
                'nickname' => 'teacher6',
                'email' => 'teacher6@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 8,
                'nickname' => 'teacher7',
                'email' => 'teacher7@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 9,
                'nickname' => 'teacher8',
                'email' => 'teacher8@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 10,
                'nickname' => 'teacher9',
                'email' => 'teacher9@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 11,
                'nickname' => 'teacher10',
                'email' => 'teacher10@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 12,
                'nickname' => 'teacher11',
                'email' => 'teacher11@gmail.com',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 13,
                'nickname' => 'student',
                'email' => 'student@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 14,
                'nickname' => 'student1',
                'email' => 'student1@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 15,
                'nickname' => 'student2',
                'email' => 'student2@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 16,
                'nickname' => 'student3',
                'email' => 'student3@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 17,
                'nickname' => 'student4',
                'email' => 'student4@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 18,
                'nickname' => 'student5',
                'email' => 'student5@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 19,
                'nickname' => 'student6',
                'email' => 'student6@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 20,
                'nickname' => 'student7',
                'email' => 'student7@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 22,
                'nickname' => 'student8',
                'email' => 'student8@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 23,
                'nickname' => 'student9',
                'email' => 'student9@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 24,
                'nickname' => 'student10',
                'email' => 'student10@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 25,
                'nickname' => 'student11',
                'email' => 'student11@gmail.com',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 26,
                'nickname' => 'bao_teacher_1',
                'email' => 'giabao+101@japanquality.asia',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 27,
                'nickname' => 'bao_teacher_2',
                'email' => 'giabao+102@japanquality.asia',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 28,
                'nickname' => 'bao_teacher_3',
                'email' => 'giabao+103@japanquality.asia',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 29,
                'nickname' => 'bao_teacher_4',
                'email' => 'giabao+104@japanquality.asia',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 30,
                'nickname' => 'bao_teacher_5',
                'email' => 'giabao+105@japanquality.asia',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 31,
                'nickname' => 'bao_student_1',
                'email' => 'giabao+1@japanquality.asia',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 32,
                'nickname' => 'bao_student_2',
                'email' => 'giabao+2@japanquality.asia',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 33,
                'nickname' => 'bao_student_3',
                'email' => 'giabao+3@japanquality.asia',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 34,
                'nickname' => 'bao_student_4',
                'email' => 'giabao+4@japanquality.asia',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 35,
                'nickname' => 'bao_student_5',
                'email' => 'giabao+5@japanquality.asia',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 36,
                'nickname' => 'test_teacher_1',
                'email' => 'test+101@japanquality.asia',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 37,
                'nickname' => 'test_teacher_2',
                'email' => 'test+102@japanquality.asia',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 38,
                'nickname' => 'test_teacher_3',
                'email' => 'test+103@japanquality.asia',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 39,
                'nickname' => 'test_teacher_4',
                'email' => 'test+104@japanquality.asia',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 40,
                'nickname' => 'test_teacher_5',
                'email' => 'test+105@japanquality.asia',
                'role' => 2,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 41,
                'nickname' => 'test_student_1',
                'email' => 'test+1@japanquality.asia',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 42,
                'nickname' => 'test_student_2',
                'email' => 'test+2@japanquality.asia',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 43,
                'nickname' => 'test_student_3',
                'email' => 'test+3@japanquality.asia',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 44,
                'nickname' => 'test_student_4',
                'email' => 'test+4@japanquality.asia',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 45,
                'nickname' => 'test_student_5',
                'email' => 'test+5@japanquality.asia',
                'role' => 3,
                'auth' => 1,
                'password' => bcrypt('12345678'),
                'created_at' => now()
            ],
            [
                'id' => 46,
                'nickname' => 'admin1',
                'email' => 'admin1@gmail.com',
                'role' => 1,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 47,
                'nickname' => 'admin2',
                'email' => 'admin2@gmail.com',
                'role' => 1,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 48,
                'nickname' => 'admin3',
                'email' => 'admin3@gmail.com',
                'role' => 1,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 49,
                'nickname' => 'admin4',
                'email' => 'admin4@gmail.com',
                'role' => 1,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 50,
                'nickname' => 'child_admin1',
                'email' => 'child_admin1@gmail.com',
                'role' => 4,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 51,
                'nickname' => 'child_admin2',
                'email' => 'child_admin2@gmail.com',
                'role' => 4,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 52,
                'nickname' => 'child_admin3',
                'email' => 'child_admin3@gmail.com',
                'role' => 4,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
            [
                'id' => 53,
                'nickname' => 'child_admin4',
                'email' => 'child_admin4@gmail.com',
                'role' => 4,
                'auth' => 1,
                'password' => bcrypt('123456789@'),
                'created_at' => now()
            ],
        ]);
    }
}
