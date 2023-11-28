<?php

namespace App\Http\Controllers\BackendSystem\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //To interact with database
use Carbon\Carbon;

class RBAC_PermissionsController extends Controller
{
    public function showRBAC_PermissionsDashboard(Request $request)
    {
        $sortBy = $request->input('sortBy');
        $sortColumn = $request->input('sortColumn');

        // Query the user data based on the search keyword, status, and date
        $query = DB::table('tbl_auth_item')
            ->where('type', 2);

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

        $permissions = $query->get();

        return view('backendSystem.rbac.permissions.rbac_PermissionsDashboard', [
            'permissions' => $permissions,
        ]);
    }

    // Function to create a new permission
    public function createPermission(Request $request)
    {

        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Perform server-side validation
        $validatedData = $request->validate([
            'permissionName' => 'required|unique:tbl_auth_item,name',
        ]);

        // Extract data from the JSON request
        $permissionName = $data['permissionName'];
        $description = $data['description'];
        $createdAt      = Carbon::now()->timestamp;
        $updatedAt      = Carbon::now()->timestamp;

        $newPermission  = DB::select( DB::raw("INSERT INTO `tbl_auth_item`(`name`, `type`, `description`, `created_at`, `updated_at`) VALUES ('$permissionName','2','$description','$createdAt', $updatedAt )") );

        return response()->json(['success' => true]);
    }

    public function showPermissionInfo($name) {
        // Query the permission data
        $permissionData = DB::table('tbl_auth_item')
            ->where('type', 2)
            ->where('tbl_auth_item.name', '=', $name)
            ->first();
    
        // Query the roles associated with the permission
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
    
        return view('backendSystem.rbac.permissions.permissionInfo', [
            'permissionData' => $permissionData,
            'roleByPermission' => $roleByPermission,
            'roleDescriptions' => $roleDescriptions,
        ]);
    }

    public function showPermissionEdit($name){
        // Query the permission data
        $permissionData = DB::table('tbl_auth_item')
            ->where('type', 2)
            ->where('tbl_auth_item.name', '=', $name)
            ->first();
    
        // To retrieve the permissionByRoles under the current permission (Start)
        // Query the roles associated with the permission
        $permissionByRoles = DB::table('tbl_auth_item_child')
            ->where('parent', $name)
            ->pluck('child');

        $permissionByRoleDescriptions = DB::table('tbl_auth_item')
            ->whereIn('name', $permissionByRoles)
            ->pluck('description', 'name');

        $excludedPermissions = $permissionByRoles->all(); // Convert the collection to an array

        $itemPermissions = DB::table('tbl_auth_item')
            ->where('type', 2)
            ->whereNotIn('name', array_merge($excludedPermissions, [$permissionData->name]))
            ->select('name', 'description')
            ->get();
    
        return view('backendSystem.rbac.permissions.permissionEdit', [
            'permissionData' => $permissionData,
            'permissionByRoles' => $permissionByRoles,
            'permissionByRoleDescriptions' => $permissionByRoleDescriptions,
            'itemPermissions' => $itemPermissions,
        ]);
    }

    public function permissionEditSave(Request $request, $permissionName)
    {
        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Extract data from the JSON request
        $permission = $data['permission'];
        $description = $data['description'];
        $permissionsArray = $data['permissionsArray'];

        //To update the permission name and description (Start)
        $authItemSql = "
        UPDATE tbl_auth_item
        SET name = '$permission',
        description = '$description'
        WHERE name = '$permissionName'
        ";

        $authItemData = DB::update($authItemSql);
        //To update the permission name and description (End)

        // To insert and remove the permission into database (Start)
        $existingChildren = DB::table('tbl_auth_item_child')
        ->where('parent', $permissionName)
        // ->pluck('child')
        ->get();

        $existingArray = [];
        foreach ($existingChildren as $key => $value) {
            array_push($existingArray, $value->child);
        }

        // To insert permissions that exist in permissionsArray but not in existingChildren
        $childrenToInsert = array_diff($permissionsArray, $existingArray);

        foreach ($childrenToInsert as $child) {
            DB::table('tbl_auth_item_child')->insert([
                'parent' => $permissionName,
                'child' => $child,
            ]);
        }

        // To remove permissions that exist in existingChildren but not in permissionsArray
        $permissionsToRemove = array_diff($existingArray, $permissionsArray);

        foreach ($permissionsToRemove as $permission) {
            DB::table('tbl_auth_item_child')
                ->where('parent', $permissionName)
                ->where('child', $permission)
                ->delete();
        }
        // To insert the missing permission into database (End)

        return response()->json(['success' => true]);
    }
    

    public function deletePermission($name)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Delete the permision
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
