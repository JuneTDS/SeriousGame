<?php

namespace App\Http\Controllers\BackendSystem\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //To interact with database

class RBAC_PermissionsController extends Controller
{
    public function showRBAC_PermissionsDashboard()
    {
        // Query the user data based on the search keyword, status, and date
        $permissions = DB::table('tbl_auth_item')
        ->where('type', 2)
        ->get();

        return view('backendSystem.rbac.permissions.rbac_PermissionsDashboard',[
            'permissions' => $permissions,
        ]);
    }

    // Function to display permissionInfo page
    public function showPermissionInfo($name){

        // Query the user data based on the search keyword, status, and date
        $permissionData = DB::table('tbl_auth_item')
        ->where('type', 2)
        ->where('tbl_auth_item.name', '=', $name)
        ->first();

        return view('backendSystem.rbac.permissions.permissionInfo',[
            'permissionData' => $permissionData,
        ]);
    }

    // Function to display permissionEdit page
    public function showPermissionEdit($id){
        return view('backendSystem.rbac.permissions.permissionEdit');
    }
}
