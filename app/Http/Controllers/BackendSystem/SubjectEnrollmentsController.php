<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectEnrollmentsController extends Controller
{
    public function showSubjectEnrollmentsDashboard()
    {
        // Retrieve data where subject_id_fk and lecturer_in_charge_id_fk pair is unique
        $uniquePairs = SubjectClass::select('subject_id_fk', 'lecturer_in_charge_id_fk')
            ->distinct()
            ->get();

        // Create an array to store the subjectEnrollments
        $subjectEnrollments = [];

        // Loop through unique pairs
        foreach ($uniquePairs as $pair) {
            // Find the subject_id that matches with subject_id_fk in tbl_subject
            $subject = Subject::where('subject_id', $pair->subject_id_fk)->first();

            // Find the lecturer_in_charge_id_fk that matches with the id in tbl_user
            $user = User::where('id', $pair->lecturer_in_charge_id_fk)->first();

            // If both subject and user are found, add the data to subjectEnrollments
            if ($subject && $user) {
                $subjectEnrollments[] = [
                    'subject_name' => $subject->subject_name,
                    'username' => $user->username,
                ];
            }
        }

        return view('backendSystem.subjectEnrollments.subjectEnrollmentsDashboard',[
            'subjectEnrollments' => $subjectEnrollments,
        ]);
    }
}
