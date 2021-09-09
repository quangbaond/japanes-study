<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FilePrivateController extends Controller
{

    /**
     * Private for images
     * [GET] file/images/{name}
     * @author: vinhppvk
     *
     * @param $name
     * @return BinaryFileResponse
     */
    public function images($name)
    {
        try {
            $storagePath = storage_path('app/images/' . $name);
            return response()->file($storagePath);
        } catch(\Exception $e) {
            abort(404);
        }
    }

    /**
     * Private for videos
     * [GET] file/videos/{name}
     * @author: vinhppvk
     *
     * @param $name
     * @return BinaryFileResponse
     */
    public function videos($name)
    {
        try {
            $storagePath = storage_path('app/videos/' . $name);
            return response()->file($storagePath);
        } catch(\Exception $e) {
            abort(404);
        }
    }
}
