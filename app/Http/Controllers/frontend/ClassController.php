<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class ClassController extends Controller
{
    public function index() {
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
        $data["classes"] = DB::table('tbl_subject')
            ->whereIn('subject_id', $subjectIds)
            ->get();

        return view('frontend.class', [
            'data' => $data
        ]);
    }

    public function search(Request $request)
    {
        $subject = $request->input("subject");

        $data["bar"] = $this->getStudentSubtopicAttemptTillFirstPass($subject, Auth::user()->id);

        $data["leaderboard"] = DB::select( DB::raw("SELECT TIME_FORMAT(SUM(`tbl_subtopic_attempt_log`.`duration`), '%H:%i:%s') as duration,
            SUM(`tbl_subtopic_attempt_log`.`score`) as score,
            `tbl_user`.`id`, `tbl_user`.`username`, COUNT(`tbl_subtopic`.`subtopic_id`) as topic_count
            FROM `tbl_subtopic_attempt_log` LEFT JOIN `tbl_subtopic` ON `tbl_subtopic`.`subtopic_id` = `tbl_subtopic_attempt_log`.`subtopic_id_fk`
            LEFT JOIN `tbl_topic` ON `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk`
            LEFT JOIN `tbl_subject` ON `tbl_subject`.`subject_id` = `tbl_topic`.`subject_id_fk`
            LEFT JOIN `tbl_user` ON `tbl_user`.`id` = `tbl_subtopic_attempt_log`.`user_id_fk` 
            where `tbl_subject`.`subject_id` = ".$subject."
            Group BY `tbl_user`.`id`,`tbl_user`.`username` ORDER BY score DESC;"));

        return response()->json(array('data'=> $data), 200);
    }

    public function getStudentSubtopicAttemptTillFirstPass($subject_id, $user_id){
        
        $subtopic_list = DB::select( DB::raw("SELECT tbl_subtopic.* FROM tbl_subtopic join tbl_topic on tbl_subtopic.topic_id_fk = tbl_topic.topic_id WHERE tbl_topic.subject_id_fk = '".$subject_id."'")); // Get list of subtopic
        
        $first_pass_array = array();
        $row_array = array();
        $calculation = array();
        $result = array();

        foreach($subtopic_list as $subtopic){
            
            /* Get the first pass row for each subtopic */
            $first_pass_sql = DB::select( DB::raw('SELECT * FROM tbl_subtopic_attempt_log where user_id_fk = '.$user_id.' and subtopic_id_fk = '.$subtopic->subtopic_id.' and no_of_star > 0  order by created_at, no_of_star asc limit 1') ); // user_id_fk = '.$user_id.'
            
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
                where user_id_fk = '.$user_id.' and subtopic_id_fk = '.$subtopic->subtopic_id.' and no_of_star = 0 and created_at < "'.$first_pass_sql[0]->created_at.'" order by created_at desc'));
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

    public function activity() {
        $data["classes"] = DB::select( DB::raw("SELECT * FROM tbl_subject_class;") );
        $data["subject"] = $_GET["subject"];

        return view('frontend.activity_tracker', [
            'data' => $data
        ]);
    }

    public function searchActivity(Request $request) {
        $classId = $request->input("class");
        $subject = $request->input("subject");

        $logs = DB::select( DB::raw("SELECT *, `tbl_game_login_log`.`created_at` as `login_at` FROM `tbl_game_login_log` left join `tbl_user` ON `tbl_user`.`id` = `tbl_game_login_log`.`user_id_fk` left join `tbl_subject_class_enrolment` on `tbl_subject_class_enrolment`.`user_id_fk` = `tbl_user`.`id` WHERE `tbl_subject_class_enrolment`.`subject_class_id_fk` = ".$classId." ORDER BY `tbl_user`.`id`, `tbl_game_login_log`.`created_at` desc;") );

        if (count($logs) == 0) {
            $data["is_exist"] = false;
            return response()->json(array('data'=> $data), 200);
            exit;
        }
        $data["logs"] = array();
        $data["is_exist"] = true;
        foreach ($logs as $key => $value) {
            if (isset($data["logs"][$value->user_id_fk])) {
                $data["logs"][$value->user_id_fk]["login_count"] = $data["logs"][$value->user_id_fk]["login_count"] + 1;
            } else {
                $data["logs"][$value->user_id_fk]["username"] = $value->username;
                $data["logs"][$value->user_id_fk]["login_count"] = 1;
                $data["logs"][$value->user_id_fk]["last_login"] = $value->login_at;
                $data["logs"][$value->user_id_fk]["user_logs"] = $this->logs($subject, $classId, $value->user_id_fk);
            }
        }

        $data["loginCount"] = DB::select( DB::raw('SELECT COUNT(first_login) as count FROM `tbl_subject_class_enrolment` as enrol 
            INNER JOIN tbl_user as user
            ON enrol.user_id_fk = user.id WHERE `subject_class_id_fk` = '.$classId.' and user.first_login LIKE "No"'));
        
        $data["noLoginCount"] = DB::select( DB::raw('SELECT COUNT(first_login) as count FROM `tbl_subject_class_enrolment` as enrol 
            INNER JOIN tbl_user as user
            ON enrol.user_id_fk = user.id WHERE `subject_class_id_fk` = '.$classId.' and user.first_login LIKE "Yes"'));
        
        return response()->json(array('data'=> $data), 200);
    }

    public function logs($subject, $classId, $userId) {

        $subTopics = DB::select( DB::raw("SELECT * FROM tbl_subtopic left join `tbl_topic` on `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk` WHERE `tbl_topic`.`subject_id_fk` = ".$subject.";"));

        $data = array();

        foreach ($subTopics as $key => $value) {
            $data[$value->subtopic_id]["subtopic"] = $value->subtopic_name;
            $data[$value->subtopic_id]["pass"] = DB::select( DB::raw(
                "SELECT count(`tbl_subtopic_attempt_log`.`subtopic_attempt_log_id`) as pass FROM `tbl_subtopic_attempt_log` left join `tbl_subtopic` on `tbl_subtopic`.`subtopic_id` = `tbl_subtopic_attempt_log`.`subtopic_id_fk` left join `tbl_topic` on `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk` Where `tbl_subtopic_attempt_log`.`user_id_fk` = ".$userId." AND `tbl_subtopic_attempt_log`.`no_of_star` > 0 AND `tbl_subtopic_attempt_log`.`subtopic_id_fk` = ".$value->subtopic_id.";"
            ) )[0]->pass;

            $data[$value->subtopic_id]["pass_at"] = DB::select( DB::raw(
                "SELECT `tbl_subtopic_attempt_log`.`created_at` as pass_at FROM `tbl_subtopic_attempt_log` left join `tbl_subtopic` on `tbl_subtopic`.`subtopic_id` = `tbl_subtopic_attempt_log`.`subtopic_id_fk` left join `tbl_topic` on `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk` Where `tbl_subtopic_attempt_log`.`user_id_fk` = ".$userId." AND `tbl_subtopic_attempt_log`.`no_of_star` > 0 AND `tbl_subtopic_attempt_log`.`subtopic_id_fk` = ".$value->subtopic_id." ORDER BY `tbl_subtopic_attempt_log`.`subtopic_attempt_log_id` DESC LIMIT 1;"
            ) );

            $data[$value->subtopic_id]["no_pass"] = DB::select( DB::raw(
                "SELECT count(`tbl_subtopic_attempt_log`.`subtopic_attempt_log_id`) as no_pass FROM `tbl_subtopic_attempt_log` left join `tbl_subtopic` on `tbl_subtopic`.`subtopic_id` = `tbl_subtopic_attempt_log`.`subtopic_id_fk` left join `tbl_topic` on `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk` Where `tbl_subtopic_attempt_log`.`user_id_fk` = ".$userId." AND `tbl_subtopic_attempt_log`.`no_of_star` = 0 AND `tbl_subtopic_attempt_log`.`subtopic_id_fk` = ".$value->subtopic_id.";"
            ) )[0]->no_pass;

            $data[$value->subtopic_id]["no_pass_at"] = DB::select( DB::raw(
                "SELECT `tbl_subtopic_attempt_log`.`created_at` as no_pass_at FROM `tbl_subtopic_attempt_log` left join `tbl_subtopic` on `tbl_subtopic`.`subtopic_id` = `tbl_subtopic_attempt_log`.`subtopic_id_fk` left join `tbl_topic` on `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk` Where `tbl_subtopic_attempt_log`.`user_id_fk` = ".$userId." AND `tbl_subtopic_attempt_log`.`no_of_star` = 0 AND `tbl_subtopic_attempt_log`.`subtopic_id_fk` = ".$value->subtopic_id." ORDER BY `tbl_subtopic_attempt_log`.`subtopic_attempt_log_id` DESC LIMIT 1;"
            ) );
        }

        return $data;
    }

    public function indepth() {
        $data["classes"] = DB::select( DB::raw("SELECT * FROM tbl_subject_class;") );
        $data["subject"] = $_GET["subject"];

        return view('frontend.indepth', [
            'data' => $data
        ]);
    }

    public function searchIndepth(Request $request) {
        $classId = $request->input("class");
        $subject = $request->input("subject");

        $logs = DB::select( DB::raw("SELECT *, `tbl_game_login_log`.`created_at` as `login_at` FROM `tbl_game_login_log` left join `tbl_user` ON `tbl_user`.`id` = `tbl_game_login_log`.`user_id_fk` left join `tbl_subject_class_enrolment` on `tbl_subject_class_enrolment`.`user_id_fk` = `tbl_user`.`id` WHERE `tbl_subject_class_enrolment`.`subject_class_id_fk` = ".$classId." ORDER BY `tbl_user`.`id`, `tbl_game_login_log`.`created_at` desc;") );

        if (count($logs) == 0) {
            $data["is_exist"] = false;
            return response()->json(array('data'=> $data), 200);
            exit;
        }
        $data["logs"] = array();
        $data["is_exist"] = true;
        foreach ($logs as $key => $value) {
            if (isset($data["logs"][$value->user_id_fk])) {
                $data["logs"][$value->user_id_fk]["login_count"] = $data["logs"][$value->user_id_fk]["login_count"] + 1;
            } else {
                $data["logs"][$value->user_id_fk]["username"] = $value->username;
                $data["logs"][$value->user_id_fk]["login_count"] = 1;
                $data["logs"][$value->user_id_fk]["last_login"] = $value->login_at;
                $data["logs"][$value->user_id_fk]["user_logs"] = $this->logs($subject, $classId, $value->user_id_fk);
            }
        }

        $data["bar"] = $this->getStudentSubtopicAttemptTillFirstPass($subject, Auth::user()->id);
        $data["statstic"] = $this->statstic($subject, $classId);

        return response()->json(array('data'=> $data), 200);
    }

    public function statstic($subject, $classId) {
        // get user list by subject and class
        $userLists = DB::select( DB::raw("SELECT `tbl_user`.*, `tbl_subject_class`.`subject_class_id`, `tbl_subject_class`.`class_name`
        FROM `tbl_user`
        LEFT JOIN `tbl_subject_class_enrolment` ON `tbl_subject_class_enrolment`.`user_id_fk` = `tbl_user`.`id`
        LEFT JOIN `tbl_subject_class` ON `tbl_subject_class`.`subject_class_id` = `tbl_subject_class_enrolment`.`subject_class_id_fk`
        WHERE `tbl_subject_class_enrolment`.`subject_class_id_fk` = ".$classId.";"));

        // $subTopics = DB::select( DB::raw("SELECT * FROM tbl_subtopic left join `tbl_topic` on `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk` WHERE `tbl_topic`.`subject_id_fk` = ".$subject.";"));

        foreach ($userLists as $key => $user) {
            $data[$user->id]["user_id"] = $user->id;
            $data[$user->id]["username"] = $user->username;
            $data[$user->id]["subject_class_id"] = $user->subject_class_id;
            $data[$user->id]["class_name"] = $user->class_name;
            $data[$user->id]["clear_topics"] = DB::select( DB::raw(
                "SELECT count(distinct(`tbl_topic`.`topic_id`)) as clear_topics FROM `tbl_subtopic_attempt_log` left join `tbl_subtopic` on `tbl_subtopic`.`subtopic_id` = `tbl_subtopic_attempt_log`.`subtopic_id_fk` left join `tbl_topic` on `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk` Where `tbl_subtopic_attempt_log`.`user_id_fk` = ".$user->id." AND `tbl_subtopic_attempt_log`.`no_of_star` > 0;"
            ) )[0]->clear_topics;

            $data[$user->id]["num_of_attempts"] = DB::select( DB::raw(
                "SELECT count(`tbl_subtopic_attempt_log`.`subtopic_attempt_log_id`) as num_of_attempts, SUM(`tbl_subtopic_attempt_log`.`score`) as `total_score`, SEC_TO_TIME(SUM(TIME_TO_SEC(`tbl_subtopic_attempt_log`.`duration`))) as duration, max(`tbl_subtopic_attempt_log`.`created_at`) as last_attempt_time FROM `tbl_subtopic_attempt_log` left join `tbl_subtopic` on `tbl_subtopic`.`subtopic_id` = `tbl_subtopic_attempt_log`.`subtopic_id_fk` left join `tbl_topic` on `tbl_topic`.`topic_id` = `tbl_subtopic`.`topic_id_fk` Where `tbl_subtopic_attempt_log`.`user_id_fk` = ".$user->id.";"
            ) );

            $data[$user->id]["total_topics"] = DB::select( DB::raw(
                "SELECT count(topic_id) as total_topics FROM `tbl_topic` WHERE `subject_id_fk` = ".$subject.";"
            ) );

            $data[$user->id]["topics"] = DB::select( DB::raw(
                "SELECT * FROM `tbl_topic` WHERE `subject_id_fk` = ".$subject.";"
            ) );
        }

        return $data;
    }
}
