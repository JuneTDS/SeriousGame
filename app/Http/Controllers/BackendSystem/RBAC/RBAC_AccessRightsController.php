<?php

namespace App\Http\Controllers\BackendSystem\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //To interact with database

class RBAC_AccessRightsController extends Controller
{
    public function showRBAC_AccessRightsDashboard()
    {
        $assigns = DB::table('tbl_user')
            ->select('tbl_user.id', 'tbl_user.username', 'tbl_auth_item.description')
            ->leftJoin('tbl_auth_assignment', 'tbl_user.id', '=', 'tbl_auth_assignment.user_id')
            ->leftJoin('tbl_auth_item', 'tbl_auth_assignment.item_name', '=', 'tbl_auth_item.name')
            ->get();

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

        return view('backendSystem.rbac.accessrights.accessRightInfo',[
            'assignData' => $assignData,
            'roleByPermission' => $roleByPermission,
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
}
