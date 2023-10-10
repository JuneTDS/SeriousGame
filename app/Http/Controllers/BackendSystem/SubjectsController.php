<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //To interact with database
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        
        $subjectname = $request->input('subjectname');
        $publish = $request->input('publish');
        $updated_by = $request->input('updated_by');
        $updated_on = $request->input('updated_on');
        $name_sort = $request->input('name_sort');

        if ($subjectname !== "" && $subjectname !== null){
            $query->where('tbl_subject.subject_name', 'like', '%' . $subjectname . '%');
        }
        
        if ($publish !== 'All' && $publish !== '' && $publish !== null) {   // Filter by status if 'All' is not selected
            $query->where('tbl_subject.published', $publish);
        }

        if ($updated_by !== 'All' && $updated_by !== '' && $updated_by !== null) {   // Filter by roleName if 'All' is not selected
            $query->where('tbl_subject.updated_by', $updated_by);
        }

        if ($updated_on !== '' && $updated_on !== null) {   // Filter by roleName if 'All' is not selected
            $query->where('tbl_subject.updated_at', '>=', $updated_on);
        }

        if ($name_sort !== '' && $name_sort !== null) {   // Filter by roleName if 'All' is not selected
            // $query->where('tbl_subject.updated_at', '>=', $updated_on);
            $query->orderBy("tbl_subject.subject_name", $name_sort);
        }

        $subjects = $query
            ->get();

        $users = DB::table('tbl_user')
            ->where('status', true)->get();

        return view('backendSystem.subjects.subjectsDashboard',[
            'subjects' => $subjects,
            'users' => $users,
            'subjectname' => $subjectname,
            'publish' => $publish,
            'updated_by' => $updated_by,
            'updated_on' => $updated_on,
            'name_sort' => $name_sort
        ]);
    }

    public function showSubjectInfo($id)
    {
        $data["subject"] = DB::table('tbl_subject')
            ->where('subject_id', $id)->get();

        // $data["topic"] = DB::table('tbl_topic')
        //     ->select(
        //         'tbl_topic.topic_id',
        //         'tbl_topic.topic_name',
        //         'COUNT(tbl_subtopic.subtopic_id) as subtopic'
        //     )
        //     ->leftJoin('tbl_subtopic', 'tbl_subtopic.topic_id_fk', '=', 'tbl_topic.topic_id')
        //     ->where('subject_id', $id)
        //     ->groupBy('tbl_topic.topic_id')
        //     ->get();

        $data["topic"] = DB::select( DB::raw("SELECT tbl_topic.topic_id, tbl_topic.topic_name, COUNT(tbl_subtopic.subtopic_id) as subtopic FROM tbl_topic LEFT JOIN tbl_subtopic ON tbl_subtopic.topic_id_fk = tbl_topic.topic_id WHERE tbl_topic.subject_id_fk = ".$id." GROUP BY tbl_topic.topic_id, tbl_topic.topic_name;") );

        // $data["subtopic"] = DB::table('tbl_subtopic')
        //     ->select(
        //         'tbl_subtopic.subtopic_id',
        //         'tbl_subtopic.subtopic_name',
        //         '(SELECT COUNT(`tbl_questions`.`question_id`) FROM `tbl_questions` WHERE `tbl_questions`.`subtopic_id_fk` = `tbl_subtopic`.`subtopic_id` AND `tbl_questions`.`question_difficulty` = "easy") as `questions_easy`',
        //         '(SELECT COUNT(`tbl_questions`.`question_id`) FROM `tbl_questions` WHERE `tbl_questions`.`subtopic_id_fk` = `tbl_subtopic`.`subtopic_id` AND `tbl_questions`.`question_difficulty` = "hard") as `questions_hard`'
        //     )
        //     ->leftJoin('tbl_topic', 'tbl_topic.topic_id', '=', 'tbl_subtopic.topic_id_fk')
        //     ->where('tbl_topic.subject_id_fk', $id)
        //     ->groupBy('tbl_subtopic.subtopic_id')
        //     ->get();

        $data["subtopic"] = DB::select( DB::raw("SELECT `tbl_subtopic`.`subtopic_id`, `tbl_subtopic`.`subtopic_name`, `tbl_subtopic`.`no_of_easy_questions`, `tbl_subtopic`.`no_of_difficult_questions`, `tbl_subtopic`.`topic_id_fk`, (SELECT COUNT(`tbl_questions`.`question_id`) FROM `tbl_questions` WHERE `tbl_questions`.`subtopic_id_fk` = `tbl_subtopic`.`subtopic_id` AND `tbl_questions`.`question_difficulty` = 'easy') as `questions_easy`, (SELECT COUNT(`tbl_questions`.`question_id`) FROM `tbl_questions` WHERE `tbl_questions`.`subtopic_id_fk` = `tbl_subtopic`.`subtopic_id` AND `tbl_questions`.`question_difficulty` = 'hard') as `questions_hard` FROM `tbl_subtopic` LEFT JOIN `tbl_topic` ON `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk` WHERE `tbl_topic`.`subject_id_fk` = ".$id." GROUP BY `tbl_subtopic`.`subtopic_id`, `tbl_subtopic`.`subtopic_name`, `tbl_subtopic`.`no_of_easy_questions`, `tbl_subtopic`.`no_of_difficult_questions`, `tbl_subtopic`.`topic_id_fk`;") );

        // SELECT `tbl_subtopic`.`subtopic_id`, `tbl_subtopic`.`subtopic_name`, 
        // (SELECT COUNT(`tbl_questions`.`question_id`) FROM `tbl_questions` WHERE `tbl_questions`.`subtopic_id_fk` = `tbl_subtopic`.`subtopic_id` AND `tbl_questions`.`question_difficulty` = "easy") as `questions_easy`,
        // (SELECT COUNT(`tbl_questions`.`question_id`) FROM `tbl_questions` WHERE `tbl_questions`.`subtopic_id_fk` = `tbl_subtopic`.`subtopic_id` AND `tbl_questions`.`question_difficulty` = "hard") as `questions_hard`
        // FROM `tbl_subtopic` 
        // LEFT JOIN `tbl_topic` ON `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk`
        // WHERE `tbl_topic`.`subject_id_fk` = 1 GROUP BY `tbl_subtopic`.`subtopic_id`;
        
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        return view('backendSystem.subjects.subjectInfo', [
            "data" => $data
        ]);
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

    public function createSubject(Request $request)
    {
        $subject = $request->input("subject");
        $createdAt = Carbon::now();

        $result = DB::insert(DB::raw("INSERT INTO `tbl_subject` (`subject_name`, `published`, `active_from`, `active_to`, `updated_at`, `updated_by`, `created_at`, `created_by`) VALUES ('".$subject."', 1, null, null, '".$createdAt."', ".Auth::user()->id.",'".$createdAt."', ".Auth::user()->id.");"));
        return response()->json(array('data'=> $result), 200);
    }

    public function changeSubjectStatus(Request $request) {
        $subjectId = $request->input("subject");
        $subject = DB::table('tbl_subject')->where('subject_id', $subjectId)->first();
        // return response()->json(array('data'=> $subject), 200);
        $published = !$subject->published;
        
        $result = DB::table('tbl_subject')->where('subject_id', $subjectId)->update(['published' => $published]);

        return response()->json(array('data'=> $result), 200);
    }

    public function updateSubject(Request $request) {
        $subjectId = $request->input("subject");
        $name = $request->input("name");
        
        $result = DB::table('tbl_subject')->where('subject_id', $subjectId)
            ->update(['subject_name' => $name]);

        return response()->json(array('data'=> $result), 200);
    }

    public function deleteSubject(Request $request) {
        $subjectId = $request->input("subject");
        
        $result = DB::table('tbl_subject')->where('subject_id', $subjectId)->delete();

        return response()->json(array('data'=> $result), 200);
    }
}
