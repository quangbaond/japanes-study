<?php

namespace App\Http\Controllers\Admin\Teachers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\teachers\LessonHistoriesRepository;
use App\Rules\CheckDateRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Timezone;

class LessonController extends Controller
{
    private $lessonHistoriesRepository;
    /**
     * LessonController constructor.
     */
    public function __construct(LessonHistoriesRepository $lessonHistoriesRepository)
    {
        $this->lessonHistoriesRepository = $lessonHistoriesRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function history()
    {
        return view('admin.teachers.lessons.history');
    }

    public function getData(Request $request) {
        $input = $request->only('email', 'nickname', 'date_from', 'date_to');
        $lessonHistories = $this->lessonHistoriesRepository->getListLessonHistories($input);
        return DataTables::of($lessonHistories)
            ->addColumn('lesson_histories_time', function ($lessonHistory) {
                return date('H:i', strtotime(Timezone::convertToLocal(\Carbon\Carbon::parse($lessonHistory->lesson_histories_date . " ". $lessonHistory->lesson_histories_time), 'Y-m-d H:i:s')));
            })
            ->addColumn('lesson_histories_date', function ($lessonHistory) {
                return Helper::formatDate(Timezone::convertToLocal(\Carbon\Carbon::parse($lessonHistory->lesson_histories_date . " ". $lessonHistory->lesson_histories_time), 'Y-m-d H:i:s'));
            })
            ->addColumn('action', function ($lessonHistory) {
                if( isset($lessonHistory->teacher_schedule_status) ) {
                    return '<span class="text-danger">未受講</span>';
                }
                return '<span>済受講</span>';
            })
            ->addColumn('course_name', function ($lessonHistory) {
                return $lessonHistory->course_name ?? '';
            })
            ->addColumn('lesson_content', function ($lessonHistory) {
                return $lessonHistory->lesson_content ?? '';
            })
            ->addColumn('created_at', function ($lessonHistory) {
                return date('Y/m/d H:i:s', strtotime(Timezone::convertToLocal(\Carbon\Carbon::parse($lessonHistory->lesson_histories_date . " ". $lessonHistory->lesson_histories_time), 'Y-m-d H:i:s')));
            })
            ->rawColumns(['lesson_histories_time','lesson_histories_date', 'course_name', 'lesson_content', 'action', 'created_at'])
            ->make(true);
    }

    /*
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchLiveNickname(Request $request) {
        $data = [];
        if($request->has('q')){
            $search = $request->q;

            $data = DB::table('users')->select('id', 'nickname')
                ->where('users.id','LIKE',"%$search%")
                ->where('users.role', '=', config('constants.role.student'))
                ->get();
        }
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchLiveEmail(Request $request) {
        $data = [];
        if($request->has('q')){
            $search = $request->q;

            $data = DB::table('users')->select('email')
                ->where('users.email','LIKE',"%$search%")
                ->where('users.role', '=', config('constants.role.student'))
                ->get();
        }
        return response()->json($data);
    }

    public function validateSearch(Request $request) {
        $input = $request->all();
        ($input['date_from'] != '' && $input['date_to'] != '') ? $rules['date_to'] = 'after_or_equal:date_from' : $rules['date_to'] = '';

        $attributes = array(
            'date_from'    => '決済日',
        );


        $date_from = explode('/',$input['date_from']);
        $date_to = explode('/', $input['date_to']);

        $input['format_date_from'] = implode('-',$date_from);
        $input['format_date_to'] = implode('-',$date_to);

        $rules['format_date_from'] = [new CheckDateRule()];
        $rules['format_date_to'] = [new CheckDateRule()];


        $message = [
            'date_to.after_or_equal'     => config('validation.after_or_equal'),
            'format_date_from.date_format'              => __('validation_custom.M020'),
            'format_date_to.date_format'                => __('validation_custom.M020'),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }
}
