<?php

namespace App\Http\Controllers\BackendSystem\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RBAC_PermissionsController extends Controller
{
    public function showRBAC_PermissionsDashboard()
    {
        return view('backendSystem.rbac.permissions.rbac_PermissionsDashboard');
    }
}
