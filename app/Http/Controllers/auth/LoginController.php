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
use Carbon\Carbon;

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
            'password'  => 'required|min:6',
            'password'  => 'required',
            // 'classcode' => 'min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $email      = $request->input("email");
        $password   = $request->input("password");
        // $classcode  = $request->input("classcode");
        $classcode  = "";
        $rememberMe = $request->input("remember");
        // exit;
        $user = User::select("tbl_user.*", "tbl_auth_assignment.item_name as role")
            ->leftJoin('tbl_auth_assignment', 'tbl_auth_assignment.user_id', '=', 'tbl_user.id')
            ->where('email', $email)
            ->where('status', '<', 3)
            ->first(); // DB::select( DB::raw("SELECT * FROM tbl_user WHERE email = '$email'") );
        
        if (!$user->is_verified) {

            $verifyEmail = app('App\Http\Controllers\auth\RegisterController')->sendVerifyLink($user->email, $user->email_confirm_token);

            return redirect()->back()
                ->withErrors(['password' => 'Your account is not verify. Link send right now.'])
                ->withInput();
        }

        $userId     = $user->id;
        $userDataSql = "UPDATE tbl_user
            SET `role` = '$user->role'
            WHERE id = $userId";

        $user = User::where('email', $email)
            ->where('status', '<', 3)
            ->first();

        // Execute the raw SQL query to update the record
        $userData = DB::update($userDataSql);

        if ($user->status == 2) {
            return redirect()->back()
                ->withErrors(['password' => 'Your account is still wait status.'])
                ->withInput();
        }

        if (!Hash::check($password, $user->password_hash)) {
            return redirect()->back()
                ->withErrors(['password' => 'Password does not match.'])
                ->withInput();
        }

        
        // print_r($user->role); exit;
        // $role       = DB::select( DB::raw("SELECT * FROM tbl_auth_assignment WHERE user_id = '$userId'") );
        // $user->role = $user->role_name;
        Auth::login($user, ($rememberMe == "on") ? true : false);

        $lastLogin      = Carbon::now()->timestamp;
        $userProfileSql = "UPDATE tbl_user_profile
            SET last_visit = $lastLogin
            WHERE user_id = $userId";
        $userProfile = DB::update($userProfileSql);

        if (!empty($classcode)) {
            $checkClassCode = DB::select(DB::raw("SELECT * FROM tbl_class_code WHERE class_code = '$classcode'"));
            
            if (count($checkClassCode) == 0) {
                return redirect()->back()
                    ->withErrors(['classcode' => 'Classcode does not match.'])
                    ->withInput();
            }

            // Check if the enrollment record exists
            $enrollmentRecord = DB::table('tbl_subject_class_enrolment')
            ->where('subject_class_id_fk', $checkClassCode->subject_class_id_fk) // Assuming 'subject_class_id_fk' should match '$classcode'
            ->where('user_id_fk', $userId)
            ->first();

            if ($enrollmentRecord) {
                return redirect()->back()
                    ->withErrors(['classcode' => 'Already join the class.'])
                    ->withInput();
            }

            $createUpdated_Time = now()->toDateTimeString();

            DB::table('tbl_subject_class_enrolment')->insert([
                'subject_class_id_fk' => $checkClassCode->subject_class_id_fk,
                'user_id_fk' => $userId,
                'updated_at' => $createUpdated_Time,
                'created_at' => $createUpdated_Time,
                'updated_by' => $userId,
                'created_by' => $userId,
            ]);
        }

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
        if (Auth::check()) {
            if ($this->checkPermission->isSuperAdmin() || $this->checkPermission->isAdmin())
            {
                // return '/home';
                return '/admin/usersDashboard';
            } else if ($this->checkPermission->isStudent())
            {
                return '/frontend/studentSubject';
            }
            return '/frontend/classes';
        }
    }
}
