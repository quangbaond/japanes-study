<?php


namespace App\Providers;


use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\ServiceProvider;
use Timezone;
use Carbon\Carbon;
class ReviewStudentProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        View::composer('*',function($view){
            $view->with('lessonHistories', $this->checkHistories());
        });
    }
    public function checkHistories() {
        if(Auth::check()){
            $data = [];
            $lesson = DB::table('lesson_histories')
                ->select('teacher_review.*' , 'lesson_histories.*')
                ->where('lesson_histories.student_id' , Auth::id())
                ->leftJoin('teacher_review' , 'teacher_review.lesson_histories_id' , '=' , 'lesson_histories.id')
                ->orderBy('lesson_histories.created_at' , 'desc')
                ->first();
            if(!empty($lesson)){
                $lesson->created_at = Timezone::convertToLocal(Carbon::parse($lesson->created_at)->addMinute(25) , 'Y-m-d H:i:s');
                if($lesson->star == null && $lesson->comment == null && $lesson->created_at <= Timezone::convertToLocal(Carbon::now() ,"Y-m-d H:i:s")){
                    $data = $lesson;
                }
            }
            return $data;
        }
    }

}
