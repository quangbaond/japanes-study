<?php

namespace App\Repositories\Admin\Managers;

use Illuminate\Support\Facades\DB;

class CourseRepository {
    /**
     * get courses
     * @return Collection
     */
    public function getCourses() {
        return DB::table('course')
            ->select('*',
                DB::raw('(SELECT COUNT(lessons.id) FROM lessons WHERE lessons.course_id = course.id) AS num_of_lessons'))
            ->get();
    }

    public function getCourseById($id) {
        return DB::table('course')
            ->select('*',
                DB::raw('(SELECT COUNT(lessons.id) FROM lessons WHERE lessons.course_id = course.id) AS num_of_lessons'))
            ->where('id', $id)
            ->first();
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getCourseInfoById($id) {
        return DB::table('lessons')
            ->select('lessons.*')
            ->where('lessons.course_id', $id)
            ->get();
    }
}
