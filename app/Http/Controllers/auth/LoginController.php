<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    // For Logout
use Illuminate\Support\Facades\Session; // For Logout

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Log out the user

        // Display a flash message
        Session::flash('success', 'Logout successful');

        return $this->show();
    }
}
