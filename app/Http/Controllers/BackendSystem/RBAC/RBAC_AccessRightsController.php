<?php

namespace App\Http\Controllers\BackendSystem\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RBAC_AccessRightsController extends Controller
{
    public function showRBAC_AccessRightsDashboard()
    {
        return view('backendSystem.rbac.permissions.rbac_AccessRightssDashboard');
    }
}
