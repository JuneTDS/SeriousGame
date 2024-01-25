<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DB;
use Illuminate\Support\Str;
use App\Http\Middleware\CheckPermission;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function __construct(Type $var = null) {
        $this->checkPermission = new CheckPermission;
    }

    public function showClassCode()
    {
        return view('auth.classcode');
    }

    public function checkClassCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classcode' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $classcode  = $request->input("classcode");
        $user       = DB::select( DB::raw("SELECT * FROM tbl_class_code WHERE class_code = '$classcode'") );
        
        if (count($user) == 0) {
            return redirect()->back()
                ->withErrors(['classcode' => 'Classcode does not match.'])
                ->withInput();
        }

        return redirect("/register");
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ingame_name'       => 'required',
            'email'             => 'required|email|unique:tbl_user',
            'password'          => 'min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password'  => 'min:6|required_with:password|same:password',
            'class_code'        => 'required|exists:tbl_class_code'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ingameName     = $request->input("ingame_name");
        $fullName       = $request->input("fullname");
        $email          = $request->input("email");
        $password       = Hash::make($request->input("password"));
        $classCode     = $request->input("class_code");

        $authKey        = Str::random(32);
        $createdAt      = Carbon::now()->timestamp;
        // exit;

        $userData       = DB::select( DB::raw("INSERT INTO `tbl_user`(`username`, `email`, `auth_key`, `password_hash`, `status`, `first_login`, `role`, `created_at`, `updated_at`) VALUES ('$fullName','$email','$authKey','$password', 1, 'Yes', 'Student', $createdAt, $createdAt )") );
        $user           = User::where('email', $email)->where('status', true)->first();
        $userProfile    = DB::select( DB::raw("INSERT INTO `tbl_user_profile`(`user_id`, `full_name`, `email_gravatar`, `admin_no`, `created_at`, `updated_at`) VALUES ('$user->id','$ingameName','$email', ' ', $createdAt, $createdAt)") );
        $userRole       = DB::select( DB::raw("INSERT INTO `tbl_auth_assignment`(`item_name`, `user_id`, `created_at`) VALUES ('Student','$user->id',$createdAt)") );

        if ($classCode != "") {
            $class          = DB::table('tbl_class_code')
                ->where('class_code', $classCode)
                ->select('subject_class_id_fk')
                ->pluck('subject_class_id_fk');
            
            $data["statstic"] = app('App\Http\Controllers\BackendSystem\LectureClassesController')->enrolStudent($class[0], $user->id);
        }

        Auth::login($user, true);

        $lastLogin      = Carbon::now()->timestamp;
        $userProfileSql = "UPDATE tbl_user_profile
            SET last_visit = $lastLogin
            WHERE user_id = $user->id";
        $userProfile = DB::update($userProfileSql);

        return redirect("/frontend/studentSubject");
    }
}
