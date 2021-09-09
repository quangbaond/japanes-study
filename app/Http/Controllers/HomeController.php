<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    private $languageActive = [
        'en',
        'vi',
        'ja',
    ];
    public function changeLanguage($language)
    {
        if ( in_array($language, $this->languageActive)) {
            Session::put('language', $language);
            return redirect()->back();
        }
    }
}
