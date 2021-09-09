<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(NationalitySeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(LessonSeeder::class);
        $this->call(UserInformationSeeder::class);
        $this->call(CourseCanTeachSeeder::class);
        $this->call(StudentCourseSeeder::class);
        $this->call(BookingSeeder::class);
        $this->call(HistoryStudentPaymentCoinSeeder::class);
        $this->call(TeacherCoinSeeder::class);
        $this->call(MasterCoinSeeder::class);
    }
}
