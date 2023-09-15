<?php

namespace App\Http\Controllers\BackendSystem\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RBAC_RolesController extends Controller
{
    public function showRBAC_RolesDashboard()
    {
        return view('backendSystem.rbac.permissions.rbac_RolesDashboard');
    }
}