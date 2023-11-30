<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //To interact with database
use App\Models\User;    //Import the User model
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function showUsersDashboard(Request $request)
    {
        $roleDescriptions = DB::table('tbl_auth_item')
        ->distinct()
        ->where('type', 1) // Add this condition to filter by type = 1
        ->pluck('description'); // To retrieve role descriptions from the filtered results

        // Get the search keyword from the input field
        $searchKeyword = $request->input('username');
        $selectedStatus = $request->input('statusDropdown');
        $selectedRoleName = $request->input('roleName');
        $selectedDate = $request->input('lastVisit');
        $sortBy = $request->input('sortBy');
        $sortColumn = $request->input('sortColumn');

        // Query the user data based on the search keyword, status, and date
        $query = DB::table('tbl_user')
        ->select('tbl_user.*', 'tbl_user_profile.last_visit', 'tbl_auth_item.description')
        ->leftJoin('tbl_user_profile', 'tbl_user.id', '=', 'tbl_user_profile.user_id')
        ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')
        ->leftJoin('tbl_auth_item', 'tbl_auth_assignment.item_name', '=', 'tbl_auth_item.name');

        if (!empty($searchKeyword) || !empty($selectedStatus) || !empty($selectedRoleName) || !empty($selectedDate)){
            if (!empty($searchKeyword)){
                $query->where(function ($query) use ($searchKeyword) {
                    $query->where('tbl_user.username', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('tbl_user.email', 'like', '%' . $searchKeyword . '%');
                });
            }
            
            if ($selectedStatus !== 'All') {   // Filter by status if 'All' is not selected
                $query->where('tbl_user.status', $selectedStatus);
            }

            if ($selectedRoleName !== 'All') {   // Filter by roleName if 'All' is not selected
                $query->where('tbl_auth_item.description', $selectedRoleName);
            }

            if (!empty($selectedDate)) {
                // // Calculate the Unix timestamps for the start and end of the day
                $startOfDay = strtotime($selectedDate . ' 00:00:00'); // First second of the day
                // $endOfDay = strtotime($selectedDate . ' 23:59:59');   // Last second of the day
                // // Query the user data based on the range of timestamps
                // $query->whereBetween('tbl_user_profile.last_visit', [$startOfDay, $endOfDay]);

                $query->where('tbl_user_profile.last_visit', '>=', $startOfDay);
            }

            // Check if $sortBy and $sortColumn are not empty and not null
            if (!empty($sortBy) && !empty($sortColumn)) {
                // Validate $sortBy as a valid sorting direction
                if ($sortBy === 'asc' || $sortBy === 'desc') {
                    // Use $sortBy and $sortColumn in the orderBy clause
                    $query->orderBy($sortColumn, $sortBy);
                } else {
                    // Default to ascending sorting if $sortBy is not valid
                    $query->orderBy($sortColumn, 'asc');
                }
            } else {
                // Default sorting if $sortBy or $sortColumn are empty or null
                $query->orderBy('id', 'asc');
            }
        }

        // Continue with sorting and retrieving the results
        $user = $query
        ->get();

        return view('backendSystem.user.userDashboard', [
            'user' => $user,
            'searchKeyword' => $searchKeyword,
            'selectedStatus' => $selectedStatus,
            'selectedRoleName' => $selectedRoleName,
            'selectedDate' => $selectedDate,
            'roleDescriptions' => $roleDescriptions,
        ]);
    }


    // Function to create a new user
    public function createUser(Request $request)
    {

        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Perform server-side validation
        // $validatedData = $request->validate([
        //     'username' => 'required|unique:tbl_user,username',
        //     'email' => 'required|email|unique:tbl_user,email',
        //     'password' => 'required',
        //     'status' => 'required|in:0,1,2,3',
        // ]);

        $checkName = DB::table('tbl_user')->where("username", $data['username'])->get();
        if (count($checkName) > 0) {
            return response()->json(['success' => false, 'message' => 'Username was already exist!']);
        }

        $checkEmail = DB::table('tbl_user')->where("email", $data['email'])->get();
        if (count($checkEmail) > 0) {
            return response()->json(['success' => false, 'message' => 'Email was already exist!']);
        }

        // Extract data from the JSON request
        $username = $data['username'];
        $email = $data['email'];
        $password_hash = Hash::make($data['password']); // Hash the password
        $status = $data['status'];

        $authKey        = Str::random(32);
        $createdAt      = Carbon::now()->timestamp;

        // return "INSERT INTO `tbl_user`(`username`, `email`, `auth_key`, `password_hash`, `status`, `first_login`, `created_at`, `updated_at`) VALUES ('$username','$email','$authKey','$password_hash', $status, 'Yes', $createdAt, $createdAt )";

        $userData       = DB::select( DB::raw("INSERT INTO `tbl_user`(`username`, `email`, `auth_key`, `password_hash`, `status`, `first_login`, `created_at`, `updated_at`) VALUES ('$username','$email','$authKey','$password_hash', $status, 'Yes', $createdAt, $createdAt )") );
        $user           = User::where('email', $email)->where('status', $status)->first();
        $userProfile    = DB::select( DB::raw("INSERT INTO `tbl_user_profile`(`user_id`, `full_name`, `email_gravatar`, `admin_no`, `created_at`, `updated_at`) VALUES ('$user->id','$username','$email', ' ', $createdAt, $createdAt)") );
        $userRole       = DB::select( DB::raw("INSERT INTO `tbl_auth_assignment`(`item_name`, `user_id`, `created_at`) VALUES ('user','$user->id',$createdAt)") );

        return response()->json(['success' => true]);
    }

    public function changeUsersDashboardStatus(Request $request)
    {
        $id = $request->input("id");
        // Retrieve the user's current status from the database
        $user = DB::table('tbl_user')->where('id', $id)->first();
        
        // Handle the case where the user doesn't exist
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Calculate the new status based on the current status
        // $newStatus = $user->status == 3 ? 0 : 1;
        switch ($user->status) {
            case 3:
                $newStatus = 2;
                break;
            case 2:
                $newStatus = 1;
                break;
            case 1:
                $newStatus = 0;
                break;
            case 0:
                $newStatus = 1;
                break;
            default:
                $newStatus = 1;
                break;
        }

        // Update the user's status in the database
        $data["result"] = DB::table('tbl_user')->where('id', $id)->update(['status' => $newStatus]);

        return response()->json(array('data'=> $data), 200);
        // Reload the userDashboard
        // return $this->showUsersDashboard();
    }

    // Function to display userInfo page
    public function showUserInfo($id)
    {
        $userData = DB::table('tbl_user')
            ->select(
                'tbl_user.*',
                'tbl_user_profile.last_visit',
                'tbl_auth_item.description',
                'tbl_user_profile.first_name',
                'tbl_user_profile.last_name',
                'tbl_user_profile.email_gravatar'
            )
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
            ->select(
                'tbl_user.*',
                'tbl_user_profile.last_visit',
                'tbl_user_profile.first_name',
                'tbl_user_profile.last_name',
                'tbl_user_profile.email_gravatar'
            )
            ->leftJoin('tbl_user_profile', 'tbl_user.id', '=', 'tbl_user_profile.user_id')
            ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')
            ->where('tbl_user.id', '=', $id)    // Retrieve the specific user's data based on the ID
            ->first();
    
        return view('backendSystem.user.userEdit', ['userData' => $userData]);
    }

    // Function to save user edit
    public function UserEditSave(Request $request)
    {
        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Perform server-side validation
        $validatedData = $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'emailGravatar' => 'required|email',
            'status' => 'required|in:0,1,2,3',
        ]);

        // Extract data from the JSON request
        $userId = $data['userId'];
        $username = $data['username'];
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $emailGravatar = $data['emailGravatar'];
        $password_hash = Hash::make($data['password']); // Hash the password
        $status = $data['status'];
        $updatedAt      = Carbon::now()->timestamp;

        if ($data['password'] != "") {
            $userDataSql = "
            UPDATE tbl_user
            SET username = '$username',
                email = '$email',
                password_hash = '$password_hash',
                status = $status,
                updated_at = $updatedAt
            WHERE id = $userId
            ";
        } else {
            $userDataSql = "
            UPDATE tbl_user
            SET username = '$username',
                email = '$email',
                status = $status,
                updated_at = $updatedAt
            WHERE id = $userId
            ";
        }

        $userProfileSql = "
        UPDATE tbl_user_profile
        SET full_name = '$username',
            email_gravatar = '$emailGravatar',
            updated_at = $updatedAt
        WHERE user_id = $userId
        ";

        // Execute the raw SQL query to update the record
        $userData = DB::update($userDataSql);
        $userProfile = DB::update($userProfileSql);

        return response()->json(['success' => true]);
    }

    // Function to display userProfile page
    public function showUsersProfile($id)
    {
        $userData = DB::table('tbl_user')
            ->select(
                'tbl_user.*',
                'tbl_user_profile.last_visit',
                'tbl_auth_item.description',
                'tbl_user_profile.first_name',
                'tbl_user_profile.last_name',
                'tbl_user_profile.email_gravatar'
            )
            ->leftJoin('tbl_user_profile', 'tbl_user.id', '=', 'tbl_user_profile.user_id')
            ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')
            ->leftJoin('tbl_auth_item', 'tbl_auth_assignment.item_name', '=', 'tbl_auth_item.name')
            ->where('tbl_user.id', '=', $id)    // Retrieve the specific user's data based on the ID
            ->first();
    
        return view('backendSystem.user.userProfile', ['userData' => $userData]);
    }

    // Function to display userProfileEdit page
    public function showUserProfileEdit($id)
    {
        $userData = DB::table('tbl_user')
            ->select(
                'tbl_user.*',
                'tbl_user_profile.first_name',
                'tbl_user_profile.last_name',
                'tbl_user_profile.email_gravatar'
            )
            ->leftJoin('tbl_user_profile', 'tbl_user.id', '=', 'tbl_user_profile.user_id')
            ->where('tbl_user.id', '=', $id)    // Retrieve the specific user's data based on the ID
            ->first();
    
        return view('backendSystem.user.userProfileEdit', ['userData' => $userData]);
    }

    // Function to save user profile edit
    public function userProfileEditSave(Request $request)
    {
        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Perform server-side validation
        $validatedData = $request->validate([
            'email' => 'required|email|unique:tbl_user,email',
            'emailGravatar' => 'required|email|unique:tbl_user,email',
        ]);

        // Extract data from the JSON request
        $userId = $data['userId'];
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $emailGravatar = $data['emailGravatar'];
        $updatedAt      = Carbon::now()->timestamp;

        $userDataSql = "
        UPDATE tbl_user
        SET email = '$email',
            first_login = 'No',
            updated_at = $updatedAt
        WHERE id = $userId
        ";

        $userProfileSql = "
        UPDATE tbl_user_profile
        SET first_name = '$firstName',
            last_name = '$lastName',
            email_gravatar = '$emailGravatar',
            updated_at = $updatedAt
        WHERE user_id = $userId
        ";

        // Execute the raw SQL query to update the record
        $userData = DB::update($userDataSql);
        $userProfile = DB::update($userProfileSql);

        return response()->json(['success' => true]);
    }

    // Function to save user profile edit
    public function userProfilePasswordSave(Request $request)
    {
        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Extract data from the JSON request
        $userId = $data['userId'];
        $current_Password = $data['current_Password'];
        $new_Password = $data['new_Password'];
        $confirm_Password = $data['confirm_Password'];
        $updatedAt      = Carbon::now()->timestamp;

        if ($new_Password !== $confirm_Password){
            return response()->json(['success' => false]);
        }
        else{
            $password_hash = Hash::make($confirm_Password); // Hash the password

            $userDataSql = "
            UPDATE tbl_user
            SET password_hash = '$password_hash',
                updated_at = $updatedAt
            WHERE id = $userId
            ";
    
            // Execute the raw SQL query to update the record
            $userData = DB::update($userDataSql);
    
            return response()->json(['success' => true]);
        }
    }

    public function deleteUser($id)
    {
        $user = DB::table('tbl_user')->where('id', $id)->first();

        // Start a database transaction
        DB::beginTransaction();

        try {

            if ($user->status === 3){
                // Delete the user and related records
                User::where('id', $id)->delete();   //Deleet user in the user table
                DB::table('tbl_user_profile')->where('user_id', $id)->delete();
                DB::table('tbl_auth_assignment')->where('user_id', $id)->delete();  // Delete the user's role from the auth_assignment table    
            } else {
                $result = DB::table('tbl_user')
                ->where('id', $id)
                ->update([
                    'status' => 3
                ]);    
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // If an error occurs during deletion, roll back the transaction
            DB::rollback();

            return response()->json(['success' => false]);
        }
    }

}
