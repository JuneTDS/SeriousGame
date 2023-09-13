<?php

namespace App\Http\Controllers\frontend\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DB;

class LoginController extends Controller
{
    public function show()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [ // <---
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect('/frontend/auth/login')
                        ->withErrors($validator)
                        ->withInput();
        }

        $email = $request->input("email");
        $password = Hash::make($request->input("password"));

        $results = DB::select( DB::raw("SELECT * FROM tbl_user WHERE email = '$email'") );
        print_r($results[0]->password_hash); echo "<br/>";
        print_r($password);
    }
}
