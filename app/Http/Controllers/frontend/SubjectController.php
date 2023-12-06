<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    //
    public function index()
    {

        // June Code
        // $data["classes"] = DB::select( DB::raw("SELECT * FROM tbl_subject;") );

        //Fendi Code
        // Get the current logged-in user's ID
        $userId = Auth::id();

        // Query to get subject_id_fk from tbl_lecturer_subject_enrolment
        $subjectIds = DB::table('tbl_lecturer_subject_enrolment')
            ->select('subject_id_fk')
            ->where('user_id_fk', $userId)
            ->pluck('subject_id_fk')
            ->toArray();

        // Query to get subject data from tbl_subject using the subject IDs obtained
        $data["subjects"] = DB::table('tbl_subject')
            ->whereIn('subject_id', $subjectIds)
            ->get();

        return view('frontend.subject', [
            'data' => $data
        ]); 
    }

    public function getGraphData(Request $request)
    {
        $userId = auth()->user()->id;
        $subject = $request->input("subject");

        $data["classes"] = DB::table('tbl_subject_class')
            ->where('subject_id_fk', $subject)
            ->where('lecturer_in_charge_id_fk', $userId)
            ->get();

        $data["bar"] = $this->getStudentSubtopicAttemptTillFirstPass($subject);

        return response()->json(array('data'=> $data), 200);
    }

    public function getStudentSubtopicAttemptTillFirstPass($subject_id)
    {
        
        $subtopic_list = DB::select( DB::raw("SELECT tbl_subtopic.* FROM tbl_subtopic join tbl_topic on tbl_subtopic.topic_id_fk = tbl_topic.topic_id WHERE tbl_topic.subject_id_fk = '".$subject_id."'")); // Get list of subtopic
        
        // print_r($subtopic_list);

        $first_pass_array = array();
        $row_array = array();
        $calculation = array();
        $result = array();

        foreach($subtopic_list as $subtopic){
            
            /* Get the first pass row for each subtopic */
            $first_pass_sql = DB::select( DB::raw('SELECT * FROM tbl_subtopic_attempt_log where subtopic_id_fk = '.$subtopic->subtopic_id.' and no_of_star > 0  order by created_at, no_of_star asc limit 1') ); // user_id_fk = '.$user_id.'
            
            // echo "<pre>";
            // print_r($first_pass_sql);
            // echo "</pre>"; exit;

            $array_holder = array();
            if(!empty($first_pass_sql)){
                $array_holder['subtopic_id'] = $subtopic->subtopic_id;
                $array_holder['subtopic_name'] = $subtopic->subtopic_name;
                $array_holder['subtopic_attempt_id'] = $first_pass_sql[0]->subtopic_attempt_log_id;
                $array_holder['subtopic_attempt_date'] = $first_pass_sql[0]->created_at;
                
                $row_array[] = $first_pass_sql;

                $failed_attempt_array_list = DB::select( DB::raw('SELECT *
                FROM tbl_subtopic_attempt_log                
                where subtopic_id_fk = '.$subtopic->subtopic_id.' and no_of_star = 0 and created_at < "'.$first_pass_sql[0]->created_at.'" order by created_at desc'));
                $row_array = array_merge($row_array,$failed_attempt_array_list);
                

                $calculation[] = array(
                    'subtopic_id' => $subtopic->subtopic_id,
                    'subtopic_name' => $subtopic->subtopic_name,
                    'score' => $first_pass_sql[0]->score,
                    'attempt_count' => count($failed_attempt_array_list)+1
                );

            }else{
                $failed_attempt_array_list = DB::select( DB::raw('SELECT *
                FROM tbl_subtopic_attempt_log                
                where subtopic_id_fk = '.$subtopic->subtopic_id.' and no_of_star = 0 order by created_at desc'));
                if(!empty($failed_attempt_array_list)){
                    $row_array = array_merge($row_array,$failed_attempt_array_list);
                }
            }
        }
        $result['calculation'] = $calculation;
        $result['rows'] = $row_array;
        return $result;
    }

    public function showStudentSubject(Request $request)
    {

        // $userId = 76;    // Temporaly use other student user ID (46)
        $userId = auth()->user()->id;

        //Get the subject id
        // $subjectId = 3;  // Temporaly use subject ID (1)
        if ($request->has('subjectFilter')) {
            $subjectId = $request->input('subjectFilter');
        } else {
            $subject = DB::table('tbl_subject_class_enrolment')
            ->select('tbl_subject_class.subject_id_fk')
            ->join('tbl_subject_class', 'tbl_subject_class.subject_class_id', '=', 'tbl_subject_class_enrolment.subject_class_id_fk')
            ->where('tbl_subject_class_enrolment.user_id_fk', $userId)
            ->first();

            if (isset($subject->subject_id_fk)) {
                return view('frontend.studentSubject',[
                    'message' => "Please contact to admin to enrol class for you.",
                    'url_parameter' => "",
                    'meterData' => "",
                ]);
            }

            $subjectId = $subject->subject_id_fk;
        }

        // Get Topic Id
        if ($request->has('classFilter')) {
            $topic_id = $request->input('classFilter');
        } else {
            $topic = DB::table('tbl_topic')
            ->select('tbl_topic.topic_id')
            ->join('tbl_subject', 'tbl_subject.subject_id', '=', 'tbl_topic.subject_id_fk')
            ->where('tbl_subject.subject_id', $subjectId)
            ->first();

            $topic_id = $topic->topic_id;
        }

        $topicName = DB::table('tbl_topic')
        ->where('topic_id', $topic_id)
        ->value('topic_name');

        // Retrieve subject IDs and names for the logged-in student
        $subjects = DB::table('tbl_subject')
            ->leftjoin('tbl_subject_class', 'tbl_subject_class.subject_id_fk', '=', 'tbl_subject.subject_id')
            ->leftjoin('tbl_subject_class_enrolment', 'tbl_subject_class_enrolment.subject_class_id_fk', '=', 'tbl_subject_class.subject_class_id')
            ->where('tbl_subject_class_enrolment.user_id_fk', $userId)
            ->select('tbl_subject.subject_id', 'tbl_subject.subject_name')
            ->distinct()
            ->get();
        
        // Call getLeaderboard function to get leaderboard data
        $meterData = $this->getProficiency($subjectId, $userId);
        $leaderboard = $this->getLeaderboard($subjectId, $userId);
        $student_statistic = $this->getStudentStatistics($topic_id, $userId);
        $student_class_enrolment_id = $this->getStudentClassEnrolmentId($userId, $subjectId);
        $url_parameter = $this->getGameLoginParameters($userId, $subjectId, $student_class_enrolment_id->subject_class_id);

        $leaderboard = collect($leaderboard); // Convert the array to a collection
        $position = $leaderboard->search(function ($item) use ($userId) {
            return $item['userId'] === $userId;
        });
        
        return view('frontend.studentSubject',[
            'subjects' => $subjects,
            'meterData' => $meterData,
            'leaderboard' => $leaderboard,
            'student_statistic' => $student_statistic,
            'position' => $position,
            'url_parameter' => $url_parameter,
            'topicName' => $topicName,
        ]);  
    }

    public function getProficiency($subject_id, $user_id)
    {
        // Get distinct subtopics cleared by the student
        $getSubtopicIds = DB::table('tbl_subtopic_attempt_log')
            ->select('tbl_subtopic_attempt_log.subtopic_id_fk as subtopic_id')
            ->distinct()
            ->join('tbl_subtopic', 'tbl_subtopic.subtopic_id', '=', 'tbl_subtopic_attempt_log.subtopic_id_fk')
            ->join('tbl_topic', 'tbl_topic.topic_id', '=', 'tbl_subtopic.topic_id_fk')
            ->join('tbl_subject', 'tbl_subject.subject_id', '=', 'tbl_topic.subject_id_fk')
            ->where('tbl_subtopic_attempt_log.user_id_fk', $user_id)
            ->where('tbl_subject.subject_id', $subject_id)
            ->where('tbl_subtopic_attempt_log.no_of_star', '>', 0)
            ->get();

        // Initialize an array to store subtopic scores for calculation
        $subtopicScores = [];

        foreach ($getSubtopicIds as $subtopicId) {
            // Get the highest score for subtopics cleared by the student
            $getSubtopicScore = DB::table('tbl_subtopic_attempt_log')
                ->select('tbl_subtopic_attempt_log.score as subtopic_score')
                ->join('tbl_subtopic', 'tbl_subtopic.subtopic_id', '=', 'tbl_subtopic_attempt_log.subtopic_id_fk')
                ->join('tbl_topic', 'tbl_topic.topic_id', '=', 'tbl_subtopic.topic_id_fk')
                ->join('tbl_subject', 'tbl_subject.subject_id', '=', 'tbl_topic.subject_id_fk')
                ->where('tbl_subtopic_attempt_log.user_id_fk', $user_id)
                ->where('tbl_subject.subject_id', $subject_id)
                ->where('tbl_subtopic_attempt_log.no_of_star', '>', 0)
                ->where('tbl_subtopic_attempt_log.subtopic_id_fk', $subtopicId->subtopic_id)
                ->orderBy('score', 'DESC')
                ->first();

            if ($getSubtopicScore) {
                array_push($subtopicScores, $getSubtopicScore->subtopic_score);
            }
        }

        $totalScore = array_sum($subtopicScores);

        // Get the maximum score of the subject
        $getMaxScoreOfSubject = DB::table('tbl_subtopic')
            ->selectRaw('SUM(tbl_subtopic.full_score) as full_score')
            ->join('tbl_topic', 'tbl_subtopic.topic_id_fk', '=', 'tbl_topic.topic_id')
            ->where('tbl_topic.subject_id_fk', $subject_id)
            ->first();

        // Calculate proficiency of the student
        if ($getMaxScoreOfSubject->full_score != 0) {
            $proficiency = ($totalScore / intval($getMaxScoreOfSubject->full_score)) * 100;
        } else {
            $proficiency = 0;
        }

        // Round to 2 decimal places
        $proficiency = round($proficiency, 2);

        return $proficiency;
    }

    public function getLeaderboard($subject_id, $user_id)
    {
        // Get class ID of the student
        $class_id = DB::table('tbl_subject_class')
        ->leftJoin('tbl_subject_class_enrolment', 'tbl_subject_class_enrolment.subject_class_id_fk', '=', 'tbl_subject_class.subject_class_id')
        ->where('tbl_subject_class_enrolment.user_id_fk', $user_id)
        ->where('tbl_subject_class.subject_id_fk', $subject_id)
        ->select('tbl_subject_class.subject_class_id')
        // ->get();
        ->first();

        // $class_id = DB::table('tbl_subject_class')
        // ->leftJoin('tbl_lecturer_subject_enrolment', 'tbl_lecturer_subject_enrolment.subject_id_fk', '=', 'tbl_subject_class.subject_id_fk')
        // ->where('tbl_lecturer_subject_enrolment.user_id_fk', $user_id)
        // ->where('tbl_subject_class.subject_id_fk', $subject_id)
        // ->select('tbl_subject_class.subject_class_id')
        // // ->get();
        // ->first();

        // Get the top 5 students
        $leader_board_query = DB::table('tbl_subtopic_attempt_log')
        ->join('tbl_subtopic', 'tbl_subtopic_attempt_log.subtopic_id_fk', '=', 'tbl_subtopic.subtopic_id')
        ->join('tbl_user', 'tbl_user.id', '=', 'tbl_subtopic_attempt_log.user_id_fk')
        ->join('tbl_subject_class_enrolment', 'tbl_user.id', '=', 'tbl_subject_class_enrolment.user_id_fk')
        ->join('tbl_subject_class', 'tbl_subject_class_enrolment.subject_class_id_fk', '=', 'tbl_subject_class.subject_class_id')
        // ->where('tbl_subject_class.subject_class_id', $class_id[0]->subject_class_id)
        ->where('tbl_subject_class.subject_class_id', $class_id->subject_class_id)
        ->groupBy('tbl_subtopic_attempt_log.user_id_fk', 'tbl_user.id', 'tbl_user.username', 'tbl_subject_class.class_name')
        ->select(
            'tbl_user.id',
            'tbl_user.username',
            'tbl_subject_class.class_name',
            DB::raw('SUM(tbl_subtopic_attempt_log.score) AS total_score'),
            DB::raw('COUNT(DISTINCT tbl_subtopic.topic_id_fk) AS highestTopic'),
            DB::raw('SEC_TO_TIME(SUM(tbl_subtopic_attempt_log.duration)) AS totalDuration')
        )
        ->orderBy('total_score', 'desc')
        ->orderBy('highestTopic', 'desc')
        ->orderBy('totalDuration', 'asc')
        ->get();

        // Get the current user's ranking
        $current_user_query = DB::table('tbl_subtopic_attempt_log')
        ->join('tbl_subtopic', 'tbl_subtopic_attempt_log.subtopic_id_fk', '=', 'tbl_subtopic.subtopic_id')
        ->join('tbl_user', 'tbl_user.id', '=', 'tbl_subtopic_attempt_log.user_id_fk')
        ->join('tbl_subject_class_enrolment', 'tbl_user.id', '=', 'tbl_subject_class_enrolment.user_id_fk')
        ->join('tbl_subject_class', 'tbl_subject_class_enrolment.subject_class_id_fk', '=', 'tbl_subject_class.subject_class_id')
        ->where('tbl_subtopic_attempt_log.best_flag', 1)
        ->where('tbl_subject_class.subject_class_id', $class_id->subject_class_id)
        ->where('tbl_user.id', $user_id)
        ->groupBy('tbl_user.id', 'tbl_subject_class.class_name', 'tbl_user.username')
        ->select(
            'tbl_user.id',
            'tbl_user.username',
            'tbl_subject_class.class_name',
            DB::raw('SUM(tbl_subtopic_attempt_log.score) AS total_score'),
            DB::raw('COUNT(DISTINCT tbl_subtopic.topic_id_fk) AS highestTopic'),
            DB::raw('SEC_TO_TIME(SUM(tbl_subtopic_attempt_log.duration)) AS totalDuration')
        )
        ->get();

        // Initialize an array to store the leaderboard data
        $leaderboard = [];

        // Extract 'id' values from $leader_board_query and store in $ranking_ids
        $ranking_ids = $leader_board_query->pluck('id')->toArray();

        if (in_array($user_id, $ranking_ids)) {
            // Add data from $leader_board_query to $leaderboard
            foreach ($leader_board_query as $data) {
                $leaderboard[] = [
                    'userId' => $data->id,
                    'username' => $data->username,
                    'total_score' => $data->total_score,
                    'topicsCleared' => $data->highestTopic,
                    'totalDuration' => $data->totalDuration,
                ];
            }
        } else {
            // Add data from $leader_board_query to $leaderboard
            foreach ($leader_board_query as $data) {
                $leaderboard[] = [
                    'userId' => $data->id,
                    'username' => $data->username,
                    'total_score' => $data->total_score,
                    'topicsCleared' => $data->highestTopic,
                    'totalDuration' => $data->totalDuration,
                ];
            }
    
            // Add the current user's data to $leaderboard
            if (count($current_user_query) > 0) {
                $leaderboard[] = [
                    'userId' => $current_user_query[0]->id,
                    'username' => $current_user_query[0]->username,
                    'total_score' => $current_user_query[0]->total_score,
                    'topicsCleared' => $current_user_query[0]->highestTopic,
                    'totalDuration' => $current_user_query[0]->totalDuration,
                ];
            }
        }
    
        return $leaderboard;
    }

    public function getStudentStatistics($topic_id, $user_id)
    {
        // Get all the subtopics cleared by the student in the topic
        $get_subtopic_id = DB::table('tbl_subtopic_attempt_log')
            ->select('tbl_subtopic_attempt_log.subtopic_id_fk as subtopic_id')
            ->distinct()
            ->leftJoin('tbl_subtopic', 'tbl_subtopic.subtopic_id', '=', 'tbl_subtopic_attempt_log.subtopic_id_fk')
            ->leftJoin('tbl_topic', 'tbl_topic.topic_id', '=', 'tbl_subtopic.topic_id_fk')
            ->leftJoin('tbl_subject', 'tbl_subject.subject_id', '=', 'tbl_topic.subject_id_fk')
            ->where('tbl_subtopic_attempt_log.user_id_fk', $user_id)
            ->where('tbl_topic.topic_id', $topic_id)
            ->where('tbl_subtopic_attempt_log.no_of_star', '>', 0)
            ->get();
        

        // Initialize an array to store student statistics
        $student_statistic = [];

        foreach ($get_subtopic_id as $subtopic) {
            // Get all of the score for subtopics cleared by the student
            $get_subtopic_score = DB::table('tbl_subtopic_attempt_log')
                ->leftJoin('tbl_subtopic', 'tbl_subtopic.subtopic_id', '=', 'tbl_subtopic_attempt_log.subtopic_id_fk')
                ->leftJoin('tbl_topic', 'tbl_topic.topic_id', '=', 'tbl_subtopic.topic_id_fk')
                ->leftJoin('tbl_subject', 'tbl_subject.subject_id', '=', 'tbl_topic.subject_id_fk')            
                ->where('tbl_subtopic_attempt_log.user_id_fk', $user_id)
                ->where('tbl_topic.topic_id', $topic_id)
                ->where('tbl_subtopic_attempt_log.no_of_star', '>', 0)
                ->where('tbl_subtopic_attempt_log.subtopic_id_fk', $subtopic->subtopic_id)
                ->orderBy('score', 'desc')
                ->select('tbl_subtopic_attempt_log.score as subtopic_score')
                ->first();

            $get_subtopic_time_taken_and_topic_name = DB::table('tbl_subtopic_attempt_log')
                ->leftJoin('tbl_subtopic', 'tbl_subtopic.subtopic_id', '=', 'tbl_subtopic_attempt_log.subtopic_id_fk')
                ->leftJoin('tbl_topic', 'tbl_topic.topic_id', '=', 'tbl_subtopic.topic_id_fk')
                ->leftJoin('tbl_subject', 'tbl_subject.subject_id', '=', 'tbl_topic.subject_id_fk')
                ->where('tbl_subtopic_attempt_log.user_id_fk', $user_id)
                ->where('tbl_topic.topic_id', $topic_id)
                ->where('tbl_subtopic_attempt_log.no_of_star', '>', 0)
                ->where('tbl_subtopic_attempt_log.subtopic_id_fk', $subtopic->subtopic_id)
                ->groupBy('tbl_subtopic.subtopic_name') // Group by subtopic_name to make all coumn in SELECT is aggregated
                ->select(
                    DB::raw('SUM(tbl_subtopic_attempt_log.duration) as time_taken'),
                    'tbl_subtopic.subtopic_name as subtopic_name'
                )
                ->get();

            $student_statistic[] = [
                'subtopic_score' => $get_subtopic_score ? $get_subtopic_score->subtopic_score : 0,
                'time_taken' => $get_subtopic_time_taken_and_topic_name->isEmpty() ? 0 : $get_subtopic_time_taken_and_topic_name[0]->time_taken,
                'subtopic_name' => $get_subtopic_time_taken_and_topic_name->isEmpty() ? '' : $get_subtopic_time_taken_and_topic_name[0]->subtopic_name,
            ];                
        }

        return $student_statistic;
    }

    public function getGameLoginParameters($user_id, $subject_id, $subject_class_enrolment_id) {
        // Define generator limits
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    
        // Setup prefix and postfix random strings
        $prefix_1 = substr(str_shuffle($permitted_chars), 0, 7);
        $prefix_2 = substr(str_shuffle($permitted_chars), 0, 7);
        $prefix_3 = substr(str_shuffle($permitted_chars), 0, 7);
        $postfix = substr(str_shuffle($permitted_chars), 0, 7);
    
        // Manipulate IDs to be in 3-digit format
        $new_user_id = sprintf('%03u', $user_id);
        $new_subject_id = sprintf('%03u', $subject_id);
        $new_subject_class_enrolment_id = sprintf('%03u', $subject_class_enrolment_id);
    
        return $prefix_1 . $new_user_id . $prefix_2 . $new_subject_id . $prefix_3 . $new_subject_class_enrolment_id . $postfix;
    }    

    public function getStudentClassEnrolmentId($user_id, $subject_id)
    {
        $get_class_id = DB::table('tbl_subject_class')
            ->leftJoin('tbl_subject_class_enrolment', 'tbl_subject_class_enrolment.subject_class_id_fk', '=', 'tbl_subject_class.subject_class_id')
            ->where('tbl_subject_class_enrolment.user_id_fk', $user_id)
            ->where('tbl_subject_class.subject_id_fk', $subject_id)
            ->select('tbl_subject_class.subject_class_id')
            ->first();

        return $get_class_id;
    }

    public function getTopic(Request $request, $id)
    {
        // Fetch topics based on the subject ID
        $topics = DB::table('tbl_topic')
            ->select('topic_id', 'topic_name')
            ->where('subject_id_fk', $id)
            ->get();
    
        return response()->json(['success' => true, 'data' => $topics]);
    }

    public function getClassIndepthForSubject(Request $request)
    {
        $subject = $request->input("subject");
        $class = $request->input("class");

        $data["topics"] = DB::table('tbl_topic')
            ->select('topic_id', 'topic_name')
            ->where('subject_id_fk', $subject)
            ->get();

        // Fetch subtopics based on the subject ID
        $data["subtopics"] = DB::table('tbl_subtopic')
            ->leftJoin('tbl_topic', 'tbl_topic.topic_id', '=', 'tbl_subtopic.topic_id_fk')
            ->leftJoin('tbl_subtopic_attempt_log', 'tbl_subtopic_attempt_log.subtopic_id_fk', '=', 'tbl_subtopic.subtopic_id')
            ->leftJoin('tbl_subject_class_enrolment', 'tbl_subject_class_enrolment.user_id_fk', '=', 'tbl_subtopic_attempt_log.user_id_fk')
            ->leftJoin('tbl_user', 'tbl_user.id', '=', 'tbl_subtopic_attempt_log.user_id_fk')
            ->select('tbl_subtopic.subtopic_id', 'tbl_subtopic.subtopic_name', 'tbl_subtopic.topic_id_fk', 'tbl_topic.topic_name',
                'tbl_user.username','tbl_subject_class_enrolment.subject_class_id_fk',
                'tbl_subtopic_attempt_log.*')
            // ->where('tbl_topic.subject_id_fk', $subject)
            ->where('tbl_subject_class_enrolment.subject_class_id_fk', $class)
            ->get();
        // SELECT tbl_subtopic.subtopic_id, tbl_subtopic_attempt_log.*, tsce.subject_class_id_fk
        // FROM tbl_subtopic
        // LEFT JOIN tbl_subtopic_attempt_log on tbl_subtopic_attempt_log.subtopic_id_fk = tbl_subtopic.subtopic_id
        // LEFT JOIN tbl_subject_class_enrolment as tsce on tsce.user_id_fk = tbl_subtopic_attempt_log.user_id_fk
        // where tsce.subject_class_id_fk = 1;

        $data["subtopics_users"] = DB::select( DB::raw("SELECT subtopic_id_fk, group_concat(user_id_fk) as subtopic_users FROM tbl_subtopic_attempt_log GROUP BY subtopic_id_fk;"));

        $data["statstic"] = app('App\Http\Controllers\frontend\ClassController')->statstic($subject, $class);
    
        return response()->json(['success' => true, 'data' => $data]);
    }
}
