<?php
namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Managers\CurriculumManagementRepository;
use Illuminate\Http\Request;

class CurriculumManagementController extends Controller {

    private $curriculumManagementRepository;
    public function __construct(CurriculumManagementRepository $curriculumManagementRepository) {
        $this->curriculumManagementRepository = $curriculumManagementRepository;
    }

    public function index() {
        return view('admin.managers.CurriculumManagement.index');
    }

    public function getListCurriculum(Request $request) {
        $input = $request->only('content', 'from_date', 'to_date');
        $this->curriculumManagementRepository->getListCurriculum($input);
    }
    public function create() {
        return view('admin.managers.CurriculumManagement.create');
    }

    public function detail($id) {
        return view('admin.managers.CurriculumManagement.detail');
    }
}
