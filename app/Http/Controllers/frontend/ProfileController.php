<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ProfileController extends Controller
{
    //
    public function index() {
        $userId = Auth::user()->id;

        $user = User::where('id', $userId)->first();

        return view('frontend.profile', [
            "user" => $user
        ]);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userId = Auth::user()->id;
        $username       = $request->input("username");
        $email          = $request->input("email");
        $password       = Hash::make($request->input("password"));

        $checkEmail = User::where('email', $email)->first();
        if ($checkEmail->id != $userId) {
            return redirect()->back()
                ->withErrors(['email' => 'The email has already been taken.'])
                ->withInput();
        }

        User::where('id', $userId)
            ->update([
                'username' => $username,
                'email' => $email,
                'password_hash' => $password,
                'updated_at' => Carbon::now()->timestamp
            ]);

        return redirect()->back()->with('message', 'Profile updated successfully.');
    }
}
