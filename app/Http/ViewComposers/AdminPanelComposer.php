<?php

namespace App\Http\ViewComposers;


use App\Repositories\Admin\students\StudentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminPanelComposer
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
        $zoom_link = $this->studentRepository->getZoomLinkHistoryByTeacherId();
        $zoom_link = $zoom_link->zoom_link ?? null;
        $teacher_zoom_link = DB::table('users')
            ->leftJoin('user_information', 'users.id', '=', 'user_information.user_id')
            ->select('users.*', 'user_information.link_zoom')
            ->where('users.id', Auth::id())
            ->first();

        $view->with('zoom_link', $zoom_link);
        $view->with('teacher_zoom_link', $teacher_zoom_link);
    }
}
