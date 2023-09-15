<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //To interact with database
use App\Models\User;    //Import the User model

class UserController extends Controller
{
    public function showUsersDashboard()
    {
        $userData = DB::table('tbl_user')
            ->select('tbl_user.*', 'tbl_user_profile.last_visit', 'tbl_auth_item.description')      //Get all user data from userTable & lastvisit data from userProfileTable & userRoleDescription from authItemTable
            ->leftJoin('tbl_user_profile', 'tbl_user.id', '=', 'tbl_user_profile.user_id')          //To compare the id in user table and user_id in userProfile table
            ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')    // Add left join to tbl_auth_assignment
            ->leftJoin('tbl_auth_item', 'tbl_auth_assignment.item_name', '=', 'tbl_auth_item.name') // Add left join to tbl_auth_item
            ->get();

        return view('backendSystem.user.userDashboard');
    }

    // Function to create a new user
    public function createUser(Request $request)
    {
        // Perform server-side validation
        $validatedData = $request->validate([
            'username' => 'required|unique:tbl_user,username',
            'email' => 'required|email|unique:tbl_user,email',
            'password' => 'required',
            'status' => 'required|in:0,1,2,3',
        ]);

        // Create a new user record
        $user = new User;
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password_hash = bcrypt($request->input('password')); // Hash the password
        $user->status = $request->input('status');
        $user->save();

        return response()->json(['success' => true]);
    }

    public function changeUsersDashboardStatus($id)
    {
        // Retrieve the user's current status from the database
        $user = DB::table('tbl_user')->where('id', $id)->first();
        
        // Handle the case where the user doesn't exist
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Calculate the new status based on the current status
        $newStatus = $user->status == 1 ? 0 : 1;

        // Update the user's status in the database
        DB::table('tbl_user')->where('id', $id)->update(['status' => $newStatus]);

        // Reload the userDashboard
        return $this->showUsersDashboard();
    }

    // Function to display userInfo page
    public function showUserInfo($id)
    {
        $userData = DB::table('tbl_user')
            ->select('tbl_user.*', 'tbl_user_profile.last_visit', 'tbl_auth_item.description')
            ->leftJoin('tbl_user_profile', 'tbl_user.id', '=', 'tbl_user_profile.user_id')
            ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')
            ->leftJoin('tbl_auth_item', 'tbl_auth_assignment.item_name', '=', 'tbl_auth_item.name')
            ->where('tbl_user.id', '=', $id)    // Retrieve the specific user's data based on the ID
            ->first();
    
        return view('backendSystem.user.userInfo', ['userData' => $userData]);
    }
    
    // Function to display userEdit page
    public function showUserEdit($id)
    {
        $userData = DB::table('tbl_user')
            ->select('tbl_user.*', 'tbl_user_profile.last_visit', 'tbl_auth_item.description')
            ->leftJoin('tbl_user_profile', 'tbl_user.id', '=', 'tbl_user_profile.user_id')
            ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')
            ->leftJoin('tbl_auth_item', 'tbl_auth_assignment.item_name', '=', 'tbl_auth_item.name')
            ->where('tbl_user.id', '=', $id)    // Retrieve the specific user's data based on the ID
            ->first();
    
        return view('backendSystem.user.userEdit', ['userData' => $userData]);
    }
    
    // Function to display userProfile page
    public function showUsersProfile()
    {
        return view('backendSystem.user.userProfile');
    }

}
