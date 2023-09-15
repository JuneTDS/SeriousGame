<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserConSubjectsControllertroller extends Controller
{
    public function showSubjectsDashboard()
    {
        return view('backendSystem.subjects.subjectsDashboard');
    }
}
