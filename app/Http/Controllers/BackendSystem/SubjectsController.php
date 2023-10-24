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

    public function showTopicsDashboard($id, Request $request)
    {

        $subject = $request->input("subject");
        $name = $request->input("name");
        $updated_by = $request->input("updated_by");
        $updated_at = $request->input("updated_at");
        $name_sort = $request->input("name_sort");

        $query = DB::table('tbl_topic')
            ->select(
                'tbl_topic.*',
                'tbl_subject.subject_name',
                'tbl_user.username as updated_by_username'
            )
            ->leftJoin('tbl_subject', 'tbl_topic.subject_id_fk', '=', 'tbl_subject.subject_id')
            ->leftJoin('tbl_user', 'tbl_topic.updated_by', '=', 'tbl_user.id')
            ->where('subject_id_fk', $id);
        
        if ($subject !== "" && $subject !== null){
            $query->where('tbl_subject.subject_id', $subject);
        }

        if ($name !== "" && $name !== null){
            $query->where('tbl_topic.topic_name', 'like', '%' . $name . '%');
        }

        if ($updated_by !== "" && $updated_by !== null){
            $query->where('tbl_topic.updated_by', $updated_by);
        }

        if ($updated_at !== "" && $updated_at !== null){
            $query->where('tbl_topic.updated_at', '>=', $updated_at);
        }

        if ($name_sort !== "" && $name_sort !== null){
            $query->orderBy("tbl_topic.topic_name", $name_sort);
        }

        // echo $query->toSql();
        $topics = $query->get();

        $subjects = DB::table('tbl_subject')->where("subject_id", $id)->get();
        $users = DB::table('tbl_user')->where("status", 1)->get();

        return view('backendSystem.topics.topicsDashboard', [
            'topics' => $topics,
            'subjects' => $subjects,
            'users' => $users,
            'urlId' => $id,
            'subject' => $subject,
            'name' => $name,
            'updated_by' => $updated_by,
            'updated_at' => $updated_at,
            'name_sort' => $name_sort,
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

    public function createTopic(Request $request) {
        $topic = $request->input("topic");
        $subject = $request->input("subject");
        $hour_dropdown = $request->input("hour_dropdown");
        $minute_dropdown = $request->input("minute_dropdown");

        $createdAt = Carbon::now();

        $result = DB::insert(DB::raw("INSERT INTO `tbl_topic` (`subject_id_fk`, `topic_name`, `ordering`, `time_expected`, `active_from`, `active_to`, `updated_at`, `updated_by`, `created_at`, `created_by`) VALUES (".$subject.", '".$topic."', 0, '".$hour_dropdown.":".$minute_dropdown.":00', null, null, '".$createdAt."', ".Auth::user()->id.",'".$createdAt."', ".Auth::user()->id.");"));
        return response()->json(array('data'=> $result), 200);
    }

    public function updateTopic(Request $request) {
        $topicId = $request->input("topic_id");
        $topic = $request->input("topic");
        $hour_dropdown = $request->input("hour_dropdown");
        $minute_dropdown = $request->input("minute_dropdown");
        
        $result = DB::table('tbl_topic')->where('topic_id', $topicId)
            ->update([
                'topic_name' => $topic,
                'time_expected' => $hour_dropdown.":".$minute_dropdown.":00"
            ]);

        return response()->json(array('data'=> $result), 200);
    }

    public function deleteTopic(Request $request) {
        $topic = $request->input("topic");
        
        $result = DB::table('tbl_topic')->where('topic_id', $topic)->delete();

        return response()->json(array('data'=> $result), 200);
    }



    // subtopic started
    public function showSubTopicsDashboard($id, Request $request) {

        $topic = $request->input("topic");
        $name = $request->input("name");
        $updated_by = $request->input("updated_by");
        $updated_at = $request->input("updated_at");
        $name_sort = $request->input("name_sort");

        $query = DB::table('tbl_subtopic')
            ->select(
                'tbl_subtopic.*',
                'tbl_topic.topic_name',
                'tbl_user.username as updated_by_username'
            )
            ->leftJoin('tbl_topic', 'tbl_topic.topic_id', '=', 'tbl_subtopic.topic_id_fk')
            ->leftJoin('tbl_user', 'tbl_subtopic.updated_by', '=', 'tbl_user.id')
            ->where('tbl_subtopic.topic_id_fk', $id);
        
        if ($topic !== "" && $topic !== null){
            $query->where('tbl_subtopic.topic_id_fk', $topic);
        }

        if ($name !== "" && $name !== null){
            $query->where('tbl_subtopic.subtopic_name', 'like', '%' . $name . '%');
        }

        if ($updated_by !== "" && $updated_by !== null){
            $query->where('tbl_subtopic.updated_by', $updated_by);
        }

        if ($updated_at !== "" && $updated_at !== null){
            $query->where('tbl_subtopic.updated_at', '>=', $updated_at);
        }

        if ($name_sort !== "" && $name_sort !== null){
            $query->orderBy("tbl_subtopic.subtopic_name", $name_sort);
        }

        // echo $query->toSql();
        $subtopics = $query->get();

        $topic = DB::table('tbl_topic')->where("topic_id", $id)->get();
        $users = DB::table('tbl_user')->where("status", 1)->get();

        return view('backendSystem.subtopics.subtopicsDashboard', [
            'subtopics' => $subtopics,
            'topic' => $topic,
            'users' => $users,
            'urlId' => $id,
            'name' => $name,
            'updated_by' => $updated_by,
            'updated_at' => $updated_at,
            'name_sort' => $name_sort,
        ]);
    }

    public function createSubTopic(Request $request) {
        $topic = $request->input("topic");
        $subtopic = $request->input("subtopic");
        $url = $request->input("url");
        $easy = $request->input("easy");
        $difficult = $request->input("difficult");
        $score = $request->input("score");

        $createdAt = Carbon::now();

        $result = DB::insert(DB::raw("INSERT INTO `tbl_subtopic` (`subtopic_name`, `ordering`, `subtopic_video_url`, `no_of_easy_questions`, `no_of_difficult_questions`, `full_score`, `boss_level_flag`, `active_from`, `active_to`, `topic_id_fk`, `updated_at`, `updated_by`, `created_at`, `created_by`) VALUES ('".$subtopic."',0,'".$url."',".$easy.",".$difficult.",".$score.",0,null,null,".$topic.",'".$createdAt."', ".Auth::user()->id.",'".$createdAt."', ".Auth::user()->id.");"));
        return response()->json(array('data'=> $result), 200);
    }

    public function updateSubTopic(Request $request) {
        $subtopicId = $request->input("subtopicId");
        $subtopic   = $request->input("subtopic");
        $url        = $request->input("url");
        $easy       = $request->input("easy");
        $difficult  = $request->input("difficult");
        $score      = $request->input("score");
        
        $result = DB::table('tbl_subtopic')
            ->where('subtopic_id', $subtopicId)
            ->update([
                'subtopic_name' => $subtopic,
                'subtopic_video_url' => $url,
                'no_of_easy_questions' => $easy,
                'no_of_difficult_questions' => $difficult,
                'full_score' => $score,
            ]);

        return response()->json(array('data'=> $result), 200);
    }

    public function deleteSubTopic(Request $request) {
        $subtopic = $request->input("subtopic");
        
        $result = DB::table('tbl_subtopic')->where('subtopic_id', $subtopic)->delete();

        return response()->json(array('data'=> $result), 200);
    }
    // end subtopic


    // question started
    public function showQuestionsDashboard($id, Request $request) {

        $subtopic = $request->input("subtopic");
        $name = $request->input("name");
        $updated_by = $request->input("updated_by");
        $updated_at = $request->input("updated_at");
        $name_sort = $request->input("name_sort");

        $query = DB::table('tbl_questions')
            ->select(
                'tbl_questions.*',
                'tbl_subtopic.subtopic_name',
                'tbl_user.username as updated_by_username'
            )
            ->leftJoin('tbl_subtopic', 'tbl_subtopic.subtopic_id', '=', 'tbl_questions.subtopic_id_fk')
            ->leftJoin('tbl_user', 'tbl_subtopic.updated_by', '=', 'tbl_user.id')
            ->where('tbl_questions.subtopic_id_fk', $id);
        
        if ($subtopic !== "" && $subtopic !== null){
            $query->where('tbl_questions.subtopic_id_fk', $subtopic);
        }

        if ($name !== "" && $name !== null){
            $query->where('tbl_questions.question_name', 'like', '%' . $name . '%');
        }

        if ($updated_by !== "" && $updated_by !== null){
            $query->where('tbl_questions.updated_by', $updated_by);
        }

        if ($updated_at !== "" && $updated_at !== null){
            $query->where('tbl_questions.updated_at', '>=', $updated_at);
        }

        if ($name_sort !== "" && $name_sort !== null){
            $query->orderBy("tbl_questions.question_name", $name_sort);
        }

        // echo $query->toSql();
        $questions = $query->get();

        $subtopics = DB::table('tbl_subtopic')->where("subtopic_id", $id)->get();
        $users = DB::table('tbl_user')->where("status", 1)->get();

        return view('backendSystem.questions.questionsDashboard', [
            'questions' => $questions,
            'subtopics' => $subtopics,
            'users' => $users,
            'urlId' => $id,
            'name' => $name,
            'updated_by' => $updated_by,
            'updated_at' => $updated_at,
            'name_sort' => $name_sort,
        ]);
    }

    public function createQuestion(Request $request) {
        $difficulty = $request->input("difficulty");
        $subtopic = $request->input("subtopic");
        $type = $request->input("type");
        $name = $request->input("name");
        $mcq_a = $request->input("mcq_a");
        $mcq_b = $request->input("mcq_b");
        $mcq_c = $request->input("mcq_c");
        $mcq_d = $request->input("mcq_d");
        $answer = $request->input("answer");
        $hint = $request->input("hint");
        $score = $request->input("score");

        $createdAt = Carbon::now();

        $result = DB::insert(DB::raw("INSERT INTO `tbl_questions` (`subtopic_id_fk`, `question_difficulty`, `question_type`, `question_name`, `mcq_a`, `mcq_b`, `mcq_c`, `mcq_d`, `question_answer`, `score`, `hints`, `boss_level_id_fk`, `updated_at`, `updated_by`, `created_at`, `created_by`) VALUES (".$subtopic.",'".$difficulty."','".$type."','".$name."','".$mcq_a."','".$mcq_b."','".$mcq_c."','".$mcq_d."','".$answer."',".$score.",'".$hint."',0,'".$createdAt."', ".Auth::user()->id.",'".$createdAt."', ".Auth::user()->id.");"));
        return response()->json(array('data'=> $result), 200);
    }

    public function updateQuestion(Request $request) {
        $questionId = $request->input("questionId");
        $difficulty = $request->input("difficulty");
        $type       = $request->input("type");
        $name       = $request->input("name");
        $mcq_a      = $request->input("mcq_a");
        $mcq_b      = $request->input("mcq_b");
        $mcq_c      = $request->input("mcq_c");
        $mcq_d      = $request->input("mcq_d");
        $answer     = $request->input("answer");
        $hint       = $request->input("hint");
        $score      = $request->input("score");
        
        $result = DB::table('tbl_questions')
            ->where('question_id', $questionId)
            ->update([
                'question_difficulty' => $difficulty,
                'question_type' => $type,
                'question_name' => $name,
                'mcq_a' => $mcq_a,
                'mcq_b' => $mcq_b,
                'mcq_c' => $mcq_c,
                'mcq_d' => $mcq_d,
                'question_answer' => $answer,
                'hints' => $hint,
                'score' => $score,
            ]);

        return response()->json(array('data'=> $result), 200);
    }

    public function deleteQuestion(Request $request) {
        $question = $request->input("question");
        
        $result = DB::table('tbl_questions')->where('question_id', $question)->delete();

        return response()->json(array('data'=> $result), 200);
    }
    // end question
}
