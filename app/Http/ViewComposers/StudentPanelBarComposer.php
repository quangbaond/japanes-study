<?php

namespace App\Http\ViewComposers;


use App\Repositories\Admin\students\StudentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentPanelBarComposer
{
    protected $studentRepository;
    /**
     * Create a movie composer.
     *
     * @return void
     */
    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        // Data course
        $courses = DB::table('course')->select('*')->get();

        $student_last_lesson = DB::table('lesson_histories')
            ->select(
                'lessons.id as lesson_id',
                'course.id as course_id',
                'lesson_histories.created_at as created_at',
                'lessons.number as lesson_number'
            )
            ->join('lessons', 'lesson_histories.lesson_id', '=', 'lessons.id')
            ->join('course', 'lessons.course_id', '=', 'course.id')
            ->where('lesson_histories.student_id', Auth::id())
            ->orderBy('lesson_histories.created_at', 'DESC')
            ->first();

        if ($student_last_lesson != null) {
            // Get number of lessons
            $number_lesson = DB::table('lessons')->select('number')->where('id', $student_last_lesson->lesson_id)->first();
            // Get number max of course
            $max_number_lesson = DB::table('lessons')->where('course_id', $student_last_lesson->course_id)->max('number');

            if (($number_lesson->number + 1) > $max_number_lesson) { // Case: course next
                $student_next_lesson = DB::table('lessons')
                    ->select('course.name as course_name', 'lessons.name as lesson_name', 'course.id as course_id', 'lessons.number')
                    ->join('course', 'course.id', '=', 'lessons.course_id')
                    ->where('course.id', $student_last_lesson->course_id + 1)
                    ->first();

            } else { // lesson next of course
                $student_next_lesson = DB::table('lessons')
                    ->select('course.name as course_name', 'lessons.name as lesson_name', 'course.id as course_id', 'lessons.number')
                    ->join('course', 'course.id', '=', 'lessons.course_id')
                    ->where('lessons.id', $student_last_lesson->lesson_id + 1)
                    ->first();
            }

        } else {
            $student_next_lesson = DB::table('lessons')
                ->select('course.name as course_name', 'lessons.name as lesson_name', 'course.id as course_id', 'lessons.number')
                ->join('course', 'course.id', '=', 'lessons.course_id')
                ->where('course.id', 1)
                ->first();
        }
        $zoom_link = $this->studentRepository->getZoomLinkByStudentId();
        $zoom_link = $zoom_link->zoom_link ?? null;

        $view->with('student_next_lesson', $student_next_lesson);
        $view->with('courses', $courses);
        $view->with('zoom_link', $zoom_link);
    }
}
