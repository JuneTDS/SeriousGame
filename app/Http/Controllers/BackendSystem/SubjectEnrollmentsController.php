<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SubjectEnrollmentsController extends Controller
{
    public function showSubjectEnrollmentsDashboard(Request $request)
    {
        $name = $request->input('name');

        $query = DB::table('tbl_lecturer_subject_enrolment')
            ->select(
                'tbl_lecturer_subject_enrolment.lecturer_subject_enrolment_id',
                'tbl_user.id',
                'tbl_user.username',
                'tbl_subject.subject_id',
                'tbl_subject.subject_name'
            )
            ->leftJoin('tbl_user', 'tbl_lecturer_subject_enrolment.user_id_fk', '=', 'tbl_user.id')
            ->leftJoin('tbl_subject', 'tbl_subject.subject_id', '=', 'tbl_lecturer_subject_enrolment.subject_id_fk');

        if (!empty($name)){
            $query->where(function ($query) use ($name) {
                $query->where('tbl_user.username', 'like', '%' . $name . '%')
                    ->orWhere('tbl_subject.subject_name', 'like', '%' . $name . '%');
            });
        }

        $subjectEnrollments = $query->get();

        // Get user IDs from tbl_auth_assignment where item_name matches the roles
        $roleUserIds = DB::table('tbl_auth_assignment')
        ->whereIn('item_name', ['Lecturer', 'Lecturer_Content_Creator', 'Lecturer_Manager'])
        ->pluck('user_id');

        // Get user IDs and usernames from tbl_user using the user IDs
        $users = DB::table('tbl_user')
        // ->whereIn('id', $roleUserIds)
        ->select('id', 'username')
        ->get();


        $subjects = DB::table('tbl_subject')->where('published', true)->get();

        return view('backendSystem.subjectEnrollments.subjectEnrollmentsDashboard',[
            'subjectEnrollments' => $subjectEnrollments,
            'users' => $users,
            'subjects' => $subjects,
            'name' => $name
        ]);
    }

    public function createEnrollment(Request $request) {
        $userId = $request->input("userId");
        $subjectId = $request->input("subjectId");
        $createdAt = Carbon::now();
        $result = DB::insert(DB::raw("INSERT INTO `tbl_lecturer_subject_enrolment` (`subject_id_fk`, `user_id_fk`, `updated_at`, `updated_by`, `created_at`, `created_by`) VALUES (".$subjectId.", ".$userId.", '".$createdAt."', ".Auth::user()->id.",'".$createdAt."', ".Auth::user()->id.");"));
        return response()->json(array('data'=> $result), 200);
    }

    public function deleteEnrollment(Request $request) {
        $enrollId = $request->input("enrollId");
        
        $result = DB::table('tbl_lecturer_subject_enrolment')->where('lecturer_subject_enrolment_id', $enrollId)->delete();

        return response()->json(array('data'=> $result), 200);
    }
}
