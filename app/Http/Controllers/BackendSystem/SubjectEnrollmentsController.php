<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectEnrollmentsController extends Controller
{
    public function showSubjectEnrollmentsDashboard()
    {
        return view('backendSystem.subjectEnrollments.subjectEnrollmentsDashboard');
    }
}
