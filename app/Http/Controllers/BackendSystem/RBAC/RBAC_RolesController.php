<?php

namespace App\Http\Controllers\BackendSystem\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //To interact with database

class RBAC_RolesController extends Controller
{
    public function showRBAC_RolesDashboard(Request $request)
    {
        $sortBy = $request->input('sortBy');
        $sortColumn = $request->input('sortColumn');

        // Query the user data based on the search keyword, status, and date
        $query = DB::table('tbl_auth_item')
            ->where('type', 1);

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
            $query->orderBy('name', 'asc');
        }
        
        $roles = $query
            ->get();

        return view('backendSystem.rbac.roles.rbac_RolesDashboard',[
            'roles' => $roles,
        ]);
    }

    // Function to create a new role
    public function createRole(Request $request)
    {

        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Perform server-side validation
        $validatedData = $request->validate([
            'roleName' => 'required|unique:tbl_auth_item,name',
        ]);

        // Extract data from the JSON request
        $roleName = $data['roleName'];
        $description = $data['description'];
        $createdAt      = Carbon::now()->timestamp;
        $updatedAt      = Carbon::now()->timestamp;

        $newRole  = DB::select( DB::raw("INSERT INTO `tbl_auth_item`(`name`, `type`, `description`, `created_at`, `updated_at`) VALUES ('$roleName','1','$description','$createdAt', $updatedAt )") );

        return response()->json(['success' => true]);
    }

    // Function to display permissionInfo page
    public function showRoleInfo($name){

        // Query the user data based on the search keyword, status, and date
        $roleData = DB::table('tbl_auth_item')
        ->where('type', 1)
        ->where('tbl_auth_item.name', '=', $name)
        ->first();

        $roleByPermission = DB::table('tbl_auth_item')
            ->select('tbl_auth_item.name', 'tbl_auth_item.description', 'tbl_auth_item_child.child')
            ->leftJoin('tbl_auth_item_child', 'tbl_auth_item.name', '=', 'tbl_auth_item_child.parent')
            ->where('tbl_auth_item.name', '=', $name)
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

        return view('backendSystem.rbac.roles.roleInfo',[
            'roleData' => $roleData,
            'roleByPermission' => $roleByPermission,
            'roleDescriptions' => $roleDescriptions,
        ]);
    }

    // Function to display permissionEdit page
    public function showRoleEdit($name){
        return view('backendSystem.rbac.roles.roleEdit');
    }

    // Delete Role
    public function deleteRole($name)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Delete the role
            DB::table('tbl_auth_item')->where('name', $name)->delete();

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