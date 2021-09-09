<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Managers\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;



class S3Controller extends Controller
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
     * Display teacher.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.s3.index');
    }

    /**
     * Create teacher.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $input = $request->all();

//        Delete image old in amazon s3
//        $name = DB::table('shop')->select('logo_image')->where('id', $input['shop_id'])->first();
//        if (!empty($name)) {
//            $array = explode("/", $name->logo_image);
//            $img = max(array_keys($array));
//            $this->deleteImageS3($array[$img]);
//        }

        //insert image new in amazon s3
        $file = $input["image_url"];
        $name = time() . $file->getClientOriginalName();
        $filePath = $name;

        // Set file upload to public
        //Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

        // Set file upload to private
        Storage::disk('s3')->put($filePath, file_get_contents($file));

        // Get url images
        if (Storage::disk('s3')->exists($filePath)) {
            $image_url = Storage::disk('s3')->url($filePath);
        } else {
            $image_url = "null";
        }

        dd($image_url);
    }

    public function show()
    {
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = Config::get('filesystems.disks.s3.bucket');
        //dd($client, $bucket);
        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => '1617263905image3.jpg'  // file name in s3 bucket which you want to access
        ]);

        //$request = $client->createPresignedRequest($command, '+2 minutes');
        $request = $client->createPresignedRequest($command, '+2 seconds');

        // Get the actual presigned-url
        $imageUrl = (string)$request->getUri();

        return view('admin.s3.show', compact('imageUrl'));
    }
}

