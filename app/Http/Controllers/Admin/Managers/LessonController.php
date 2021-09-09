<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Exports\LessonHistoriesExport;
use App\Helpers\Helper;
use App\Repositories\Admin\Managers\BookingListRepository;
use App\Repositories\Admin\Managers\TeacherRepository;
use App\Rules\CheckDateRule;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Timezone;
class LessonController extends Controller
{
    protected $teacherRepository;

    /**
     * LessonController constructor.
     * @param TeacherRepository $teacherRepository
     */
    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }


    /**
     * @return View
     */
    public function lessonHistory()
    {
        $first_day_of_month = TimeZone::convertToLocal(Carbon::now()->startOfMonth(),'Y/m/d');
        $today = TimeZone::convertToLocal(Carbon::now(),'Y/m/d');
        return view('admin.managers.lessons.lessonHistory', compact('first_day_of_month', 'today'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeacherNicknameById(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data = DB::table('users')->select('id', 'nickname')
                ->where('id', 'like', '%' . $search . '%')
                ->whereIn('role', [config('constants.role.teacher'), config('constants.role.student')])
                ->get();
        }
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeacherNicknameByEmail(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data = DB::table('users')->select('id', 'email')
                ->where('email', 'LIKE', "%$search%")
                ->whereIn('role', [config('constants.role.teacher'), config('constants.role.student')])
                ->get();
        }
        return response()->json($data);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationSearch(Request $request)
    {
        // Data request
        $input = $request->all();

        // Rule validation
        $rules = [];
        ($input['date_from'] != '' && $input['date_to'] != '') ? $rules['date_to'] = 'after_or_equal:date_from' : $rules['date_to'] = '';

        $date_from = explode('/', $input['date_from']);
        $date_to = explode('/', $input['date_to']);

        $input['format_date_from'] = implode('-',$date_from);
        $input['format_date_to'] = implode('-',$date_to);

        $rules['format_date_from'] = [new CheckDateRule()];
        $rules['format_date_to'] = [new CheckDateRule()];

        // Message validation
        $message = [
            'date_to.after_or_equal'                    => config('validation.after_or_equal'),
            'format_date_from.date_format'              => __('validation_custom.M020'),
            'format_date_to.date_format'                => __('validation_custom.M020'),
        ];

        $validator = Validator::make($input, $rules, $message);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function statisticDataTable()
    {
        $statistics = $this->teacherRepository->getTeacherStatistic();
        return DataTables::of($statistics)
            ->make(true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function lessonHistoriesDataTable()
    {
        $lesson_histories = $this->teacherRepository->getTeacherLessonHistories();

        return DataTables::of($lesson_histories)
            ->addColumn('history_status', function ($history) {
                if(isset($history->teacher_schedule_status) && $history->teacher_schedule_status == 2)
                    return '<span class="text-danger">未受講</span>';
                else {
                    return '<span class="">済受講</span>';
                }
            })
            ->addColumn('lesson_histories_date', function ($history) {
                return Helper::formatDate($history->lesson_histories_date);
            })
            ->rawColumns(['history_status', 'lesson_histories_date'])
            ->make(true);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportToExcel()
    {
        isset($_GET['date_from']) ? $date_from = $_GET['date_from'] : $date_from = '';
        isset($_GET['date_to']) ? $date_to = $_GET['date_to'] : $date_to = '';
        $statistics = $this->teacherRepository->getTeacherStatistic();

        return Excel::download(new LessonHistoriesExport($statistics, $date_from, $date_to), 'statistics.xlsx');
    }
}
