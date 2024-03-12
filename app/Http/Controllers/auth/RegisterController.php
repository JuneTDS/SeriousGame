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

use Mailgun\Mailgun;
use GuzzleHttp\Client;

use Illuminate\Support\Facades\Mail;
use App\Mail\Verify;

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

        DB::beginTransaction();
        
        $token = '';
        $verified = 1;
        if (env('ACCOUNT_VERIFY') == true) {
            $verified = 0;
            $token          = (string) Str::uuid();
        }

        $userData       = DB::select( DB::raw("INSERT INTO `tbl_user`(`username`, `email`, `auth_key`, `password_hash`, `status`, `first_login`, `role`, `is_verified`, `email_confirm_token`, `created_at`, `updated_at`) VALUES ('$fullName','$email','$authKey','$password', 1, 'Yes', 'Student', $verified, '$token', $createdAt, $createdAt )") );
        $user           = User::where('email', $email)->where('status', true)->first();
        $userProfile    = DB::select( DB::raw("INSERT INTO `tbl_user_profile`(`user_id`, `full_name`, `email_gravatar`, `admin_no`, `created_at`, `updated_at`) VALUES ('$user->id','$ingameName','$email', ' ', $createdAt, $createdAt)") );
        $userRole       = DB::select( DB::raw("INSERT INTO `tbl_auth_assignment`(`item_name`, `user_id`, `created_at`) VALUES ('Student','$user->id',$createdAt)") );

        if ($classCode != "") {

            $classSize = DB::table('tbl_class_code')
                ->where('class_code', $classCode)
                ->first();

            //Check number of user in the class
            $usersInClassCount = DB::table('tbl_subject_class_enrolment')
                ->where('subject_class_id_fk', $classSize->subject_class_id_fk)
                ->count();

            // Calculate the remaining class size
            if ($classSize->class_size > $usersInClassCount) {

                $class = DB::table('tbl_class_code')
                    ->where('class_code', $classCode)
                    ->select('subject_class_id_fk')
                    ->pluck('subject_class_id_fk');

                $data["statstic"] = app('App\Http\Controllers\BackendSystem\LectureClassesController')->enrolStudent($class[0], $user->id, $classCode);

                DB::commit();
            } else {
                DB::rollback();
                return redirect()->back()->with('error', "Class size is full, please contact your lecturer")->withInput();
            }
        }

        if (env('ACCOUNT_VERIFY') == true) {
            $this->sendVerifyLink($email, $token);
            return redirect("/register/success");
        } else {
            return redirect("/login")->with('message', "Your account was registered successfully.");
        }

        
    }

    public function registerSuccess() {
        return view('auth.success');
    }

    public function testMail() {
        // # Instantiate the client.
        // $mgClient = Mailgun::create(env('MAILGUN_SECRET')); // new Mailgun(env('MAILGUN_SECRET'), $client);
        // $domain = env('MAILGUN_DOMAIN');
        // # Make the call to the client.
        // $result = $mgClient->messages()->send($domain, array(
        //     'from'	=> 'Excited User <mailgun@test.com>',
        //     'to'	=> 'tds.mm.dev004@gmail.com',
        //     'subject' => 'Hello',
        //     'text'	=> 'Testing some Mailgun awesomeness!'
        // ));

        $data = array(
            'url'	=> route('verify', ['token' => 'token'])
        );
        Mail::to("tds.mm.dev004@gmail.com")
            ->send(new Verify($data));
    }

    public function sendVerifyLink($email, $token) {
        $data = array(
            'url'	=> route('verify', $token)
        );
        Mail::to($email)->send(new Verify($data));
    }

    public function verifyAccount(Request $request, $token) {
        $user = User::where('email_confirm_token', $token)->where('is_verified', false)->first();
        $updatedAt = Carbon::now()->timestamp;

        if (!empty($user)) {
            $userDataSql = "
                UPDATE tbl_user
                SET email_confirm_token = null,
                    is_verified = true,
                    updated_at = $updatedAt
                WHERE id = $user->id
            ";

            $userData = DB::update($userDataSql);
        }
        return view('auth.verifySuccess');
    }
}
