<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Managers\CourseRepository;

class CourseController extends Controller {

    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function indexCourse() {
        $courses = $this->courseRepository->getCourses();
        return view('admin.managers.courses.index', compact('courses'));
    }

    /**
     * get course detail
     * @param $id
     * @return View
     */
    public function detailCourse($id) {
        $course = $this->courseRepository->getCourseById($id);
        if($course == null) {
            abort(404);
        }
        $lessons = $this->courseRepository->getCourseInfoById($id);
        return view('admin.managers.courses.detail', compact('course', 'lessons'));
    }
}
