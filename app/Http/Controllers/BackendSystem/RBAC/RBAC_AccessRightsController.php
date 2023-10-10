<?php

namespace App\Http\Controllers\BackendSystem\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //To interact with database
use App\Models\User;    //Import the User model

class RBAC_AccessRightsController extends Controller
{
    public function showRBAC_AccessRightsDashboard(Request $request)
    {
        $sortBy = $request->input('sortBy');
        $sortColumn = $request->input('sortColumn');

        $query = DB::table('tbl_user')
            ->select('tbl_user.id', 'tbl_user.username', 'tbl_auth_item.description')
            ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')
            ->leftJoin('tbl_auth_item', 'tbl_auth_assignment.item_name', '=', 'tbl_auth_item.name');

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

        $assigns = $query->get();

        return view('backendSystem.rbac.accessrights.rbac_AccessRightssDashboard',[
            'assigns' => $assigns,
        ]);
    }

    // Function to display accessRightInfo page
    public function showAccessRightInfo($id)
    {
        $assignData = DB::table('tbl_user')
            ->select('tbl_user.id', 'tbl_user.username', 'tbl_auth_item.description')
            ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')
            ->leftJoin('tbl_auth_item', 'tbl_auth_assignment.item_name', '=', 'tbl_auth_item.name')
            ->where('tbl_user.id', '=', $id)
            ->first();

        $roleByPermission = DB::table('tbl_user')
            ->select('tbl_user.id', 'tbl_user.username', 'tbl_auth_item.description', 'tbl_auth_item_child.child')
            ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')
            ->leftJoin('tbl_auth_item', 'tbl_auth_assignment.item_name', '=', 'tbl_auth_item.name')
            ->leftJoin('tbl_auth_item_child', 'tbl_auth_item.name', '=', 'tbl_auth_item_child.parent')
            ->where('tbl_user.id', '=', $id)
            ->get();

        // Create an array to store role descriptions
        $roleDescriptions = [];
    
        // Iterate through the roles and fetch descriptions
        foreach ($roleByPermission as $role) {
            // Query the description for each role
            $roleDescription = DB::table('tbl_auth_item')
                ->where('name', $role->child)
                ->where('type', 2) // Assuming roles have type 1
                ->value('description');
    
            // Store the description in the array
            $roleDescriptions[$role->child] = $roleDescription;
        }

        return view('backendSystem.rbac.accessrights.accessRightInfo',[
            'assignData' => $assignData,
            'roleByPermission' => $roleByPermission,
            'roleDescriptions' => $roleDescriptions,
        ]);
    }

    // Function to display accessRightEdit page
    public function showaccessRightEdit($id)
    {
        $assignData = DB::table('tbl_user')
            ->select('tbl_user.id', 'tbl_auth_item.description')
            ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')
            ->leftJoin('tbl_auth_item', 'tbl_auth_assignment.item_name', '=', 'tbl_auth_item.name')
            ->where('tbl_user.id', '=', $id)
            ->first();

        $roleDescriptions = DB::table('tbl_auth_item')
        ->distinct()
        ->where('type', 1)
        ->pluck('description');

        return view('backendSystem.rbac.accessrights.accessRightEdit',[
            'assignData' => $assignData,
            'roleDescriptions' => $roleDescriptions,
        ]);
    }

    // Function to save user edit
    public function accessRightEditSave(Request $request)
    {
        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Extract data from the JSON request
        $userId = $data['userId'];
        $roleDescription = $data['roleDescription'];    

        $roleSql = "
            UPDATE tbl_auth_assignment AS assignment
            INNER JOIN tbl_auth_item AS item ON assignment.item_name = item.name
            SET assignment.item_name = item.name
            WHERE assignment.user_id = $userId
            AND item.description = '$roleDescription'
        ";

        // Execute the raw SQL query to update the record
        $roleData = DB::update($roleSql);

        return response()->json(['success' => true]);
    }

    public function deleteAssignRight($id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Delete the user and related records
            User::where('id', $id)->delete();   //Deleet user in the user table
            DB::table('tbl_user_profile')->where('user_id', $id)->delete();
            DB::table('tbl_auth_assignment')->where('user_id', $id)->delete();  // Delete the user's role from the auth_assignment table

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
