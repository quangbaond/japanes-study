<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Managers\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Helpers\Helper;
use Validator;
use DB;

class UserController extends Controller
{
    protected $userRepository;

    /**
     * Display a listing of the resource.
     *
     * @param UserRepository $userRepository
     */
    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display users.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.managers.users.index');
    }

    /**
     * Display user datatable.
     *
     */
    public function userDataTable()
    {
        $fieldTable = (!empty($_GET["fieldTable"])) ? ($_GET["fieldTable"]) : ('');
        $valueSearch = (!empty($_GET["valueSearch"])) ? ($_GET["valueSearch"]) : ('');
        if ($valueSearch && $fieldTable) {
            $users = $this->userRepository->userDataTableSearch($fieldTable, $valueSearch);
        } else {
            $users = $this->userRepository->userDataTable();
        }



        return Datatables::of($users)
            ->addColumn('user-online', function ($user) {
                if (Cache::has('user-is-online-' . $user->id)) {
                    return '<span class="text-success">Online</span>';
                } else {
                    return '<span class="text-danger">Offline</span>';
                }
            })
            ->addColumn('action', function ($user) {
                return '<a href="' . route('user.detail', $user->id) . '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i>Detail</a>
                        <a href="' . route('user.edit', $user->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i>Edit</a>';
            })
            ->addColumn('created_at', function ($user) {
                return Helper::formatDate($user->created_at);
            })
            ->addColumn('checkbox', function ($user) {
                return '<input type="checkbox" class="chk_item" value="' .$user->id. '" name="user_id" />';
            })
            ->rawColumns(['user-online','action','created_at','checkbox'])
            ->make(true);
    }

    /**
     * Detail user
     *
     * @param $id
     * @return View
     */
    public function detail($id)
    {
        $user = $this->userRepository->detail($id);
        if (!empty($user)) {
            return view('admin.managers.users.detail', compact('user'));
        } else {
            return view('errors.no_data');
        }
    }

    /**
     * Edit user
     *
     * @param $id
     * @return View
     */
    public function edit($id)
    {
        $user = $this->userRepository->edit($id);
        if (!empty($user)) {
            return view('admin.managers.users.edit', compact('user'));
        } else {
            return view('errors.no_data');
        }

    }

    /**
     * Update user
     *
     * @param UserRequest $request
     * @return RedirectResponse
     */
    public function update(UserRequest $request)
    {
        $input = $request->all();
        if ($this->userRepository->update($input)) {
            return redirect()->route('user')->with('success', __('notification.update-success'));
        } else {
            return redirect()->route('user')->with('error', __('notification.update-error'));
        }
    }

    /**
     * Delete user
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $input = $request->all();
        User::destroy($input['id']);
        return redirect()->route('user')->with('success', __('notification.delete-success'));
    }

    /**
     * DeleteAll user
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAll(Request $request)
    {
        $input = $request->all();
        User::whereIn('id',$input['user_id'])->delete();
        return redirect()->route('user')->with('success', __('notification.delete-success'));
    }
}

