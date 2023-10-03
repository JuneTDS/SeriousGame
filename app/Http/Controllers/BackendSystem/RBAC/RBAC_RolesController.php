<?php

namespace App\Http\Controllers\BackendSystem\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //To interact with database

class RBAC_RolesController extends Controller
{
    public function showRBAC_RolesDashboard()
    {
        // Query the user data based on the search keyword, status, and date
        $roles = DB::table('tbl_auth_item')
        ->where('type', 1)
        ->get();

        return view('backendSystem.rbac.roles.rbac_RolesDashboard',[
            'roles' => $roles,
        ]);
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

        return view('backendSystem.rbac.roles.roleInfo',[
            'roleData' => $roleData,
            'roleByPermission' => $roleByPermission,
        ]);
    }

    // Function to display permissionEdit page
    public function showRoleEdit($name){
        return view('backendSystem.rbac.roles.roleEdit');
    }
}