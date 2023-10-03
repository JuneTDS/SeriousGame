<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //To interact with database

class SubjectsController extends Controller
{
    public function showSubjectsDashboard(Request $request)
    {
        $query = DB::table('tbl_subject')
            ->select(
                'tbl_subject.*',
                'tbl_user.username as updated_by_username'
            )
            ->leftJoin('tbl_user', 'tbl_subject.updated_by', '=', 'tbl_user.id');

        $subjects = $query
            ->get();

        return view('backendSystem.subjects.subjectsDashboard',[
            'subjects' => $subjects,
        ]);
    }

    public function showSubjectInfo($id)
    {
        return view('backendSystem.subjects.subjectInfo');
    }

    public function showTopicsDashboard($id)
    {
        $topics = DB::table('tbl_topic')
            ->select(
                'tbl_topic.*',
                'tbl_subject.subject_name',
                'tbl_user.username as updated_by_username'
            )
            ->leftJoin('tbl_subject', 'tbl_topic.subject_id_fk', '=', 'tbl_subject.subject_id')
            ->leftJoin('tbl_user', 'tbl_topic.updated_by', '=', 'tbl_user.id')
            ->where('subject_id_fk', $id)
            ->get();

        return view('backendSystem.topics.topicsDashboard', [
            'topics' => $topics,
        ]);
    }
}
