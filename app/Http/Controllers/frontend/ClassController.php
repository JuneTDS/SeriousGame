<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ClassController extends Controller
{
    public function index()
    {
        $data["classes"] = DB::select( DB::raw("SELECT * FROM tbl_class_code;") );

        return view('frontend.class', [
            'data' => $data
        ]);
    }
}
