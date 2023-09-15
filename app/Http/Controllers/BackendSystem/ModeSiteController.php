<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModeSiteController extends Controller
{
    public function showModeSiteDashboard()
    {
        return view('backendSystem.modeSite.modeSiteDashboard');
    }
}
