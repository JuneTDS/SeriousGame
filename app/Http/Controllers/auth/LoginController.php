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
            'classcode' => 'min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $email      = $request->input("email");
        $password   = $request->input("password");
        $classcode  = $request->input("classcode");
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
                return '/frontend/classes';
            }
            return '/frontend/classes';
        }
    }
}
