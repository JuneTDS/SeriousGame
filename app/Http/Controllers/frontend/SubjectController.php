<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class SubjectController extends Controller
{
    //
    public function index() {
        $data["classes"] = DB::select( DB::raw("SELECT * FROM tbl_subject;") );

        return view('frontend.subject', [
            'data' => $data
        ]);
    }

    public function getGraphData(Request $request) {
        $subject = $request->input("subject");

        $data["bar"] = $this->getStudentSubtopicAttemptTillFirstPass($subject);

        return response()->json(array('data'=> $data), 200);
    }

    public function getStudentSubtopicAttemptTillFirstPass($subject_id){
        
        $subtopic_list = DB::select( DB::raw("SELECT tbl_subtopic.* FROM tbl_subtopic join tbl_topic on tbl_subtopic.topic_id_fk = tbl_topic.topic_id WHERE tbl_topic.subject_id_fk = '.$subject_id.'")); // Get list of subtopic
        
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
}
