<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\CheckPermission;

class LoginController extends Controller
{
    public function __construct(Type $var = null) {
        $this->checkPermission = new CheckPermission;
    }

    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            // 'password'  => 'required|min:6',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $email      = $request->input("email");
        $password   = $request->input("password");
        $rememberMe = $request->input("remember");
        // exit;
        $user = User::where('email', $email)->where('status', true)->first(); // DB::select( DB::raw("SELECT * FROM tbl_user WHERE email = '$email'") );
        
        if (!Hash::check($password, $user->password_hash)) {
            return redirect()->back()
                ->withErrors(['password' => 'Password does not match.'])
                ->withInput();
        }

        $userId     = $user->id;
        $role       = DB::select( DB::raw("SELECT * FROM tbl_auth_assignment WHERE user_id = '$userId'") );
        $user->role = $role[0]->item_name;

        Auth::login($user, ($rememberMe == "on") ? true : false);

        $path = $this->redirectAfterLogin();

        return redirect($path);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Log out the user

        // Display a flash message
        Session::flash('success', 'Logout successful');

        return $this->show();
    }

    private function redirectAfterLogin() {
        if ($this->checkPermission->isSuperAdmin() || $this->checkPermission->isAdmin())
        {
            // return '/home';
            return '/admin/usersDashboard';
        } else if ($this->checkPermission->isStudent())
        {
            return '/home';
        }
    }
}
