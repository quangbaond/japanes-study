<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Controllers\TimezoneController;

class Select2AutocompleteController extends Controller
{

    protected $timezone;
    /**
     * Display a listing of the resource.
     *
     * @param TimezoneController $timezone
     */
    function __construct(TimezoneController $timezone)
    {
        $this->timezone = $timezone;
    }


    /**
     * Show the application layout.
     * @author: vinhppvk
     *
     * @return View
     */
    public function index()
    {
        return view('admin.managers.select2.index');
    }

    /**
     * Show the application dataAjax.
     * @param Request $request
     * @return JsonResponse
     * @author: vinhppvk
     *
     */
    public function dataAjax(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;

            $data = DB::table('users')->select('id', 'email')
                ->where('email','LIKE',"%$search%")
                ->whereNull('deleted_at')
                ->get();
        }
        return response()->json($data);
    }

    public function timezone()
    {
        return view('admin.managers.select2.timezone');
    }

    public function convertTimezone(Request $request)
    {
        $input = $request->all();
        $convert = $this->timezone->convertFromLocal(Carbon::parse($input['timezone'])->format('Y-m-d H:i:s'));
        dd($convert);
    }
}
