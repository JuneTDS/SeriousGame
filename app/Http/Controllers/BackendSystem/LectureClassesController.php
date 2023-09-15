<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LectureClassesController extends Controller
{
    public function showLectureClassesDashboard()
    {
        return view('backendSystem.lectureClasses.lectureClassesDashboard');
    }
}
