<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class FeedbackController extends Controller
{
    public function index() {
        $data["classes"] = DB::select( DB::raw("SELECT * FROM tbl_subject;") );

        return view('frontend.feedback', [
            'data' => $data
        ]);
    }

    public function getClassesAndTopicBySubject(Request $request) {
        $subject = $request->input("subject");

        $data["classes"] = DB::select( DB::raw("SELECT * FROM `tbl_subject_class` WHERE `subject_id_fk` = ".$subject.";") );
        $data["topics"] = DB::select( DB::raw("SELECT * FROM `tbl_topic` WHERE `subject_id_fk` = ".$subject.";") );

        return response()->json(array('data'=> $data), 200);
    }

    public function getFeedbacks(Request $request) {
        $subject = $request->input("subject");
        $class = $request->input("class");
        $topic = $request->input("topic");
        $topicTwo = $request->input("topicTwo");

        $conditionTopic = "";
        $conditionGeneral = "";
        if ($subject != "") {
            $conditionTopic = "`subject_id_fk` = ".$subject;
            $conditionGeneral = "`subject_id_fk` = ".$subject;
        }
        if ($class != "") {
            if ($conditionTopic != "") {
                $conditionTopic .= " AND `subject_class_id_fk` = ".$class;
                $conditionGeneral .= " AND `subject_class_id_fk` = ".$class;
            } else {
                $conditionTopic = "`subject_class_id_fk` = ".$class;
                $conditionGeneral = "`subject_class_id_fk` = ".$class;
            }
        }
        if ($topic != "") {
            if ($conditionTopic != "") {
                $conditionTopic .= " AND `topic_id_fk` = ".$topic;
            } else {
                $conditionTopic = "`topic_id_fk` = ".$topic;
            }
        }
        if ($topicTwo != "") {
            if ($conditionGeneral != "") {
                $conditionGeneral .= " AND `topic_id_fk` = ".$topicTwo;
            } else {
                $conditionGeneral = "`topic_id_fk` = ".$topicTwo;
            }
        }

        $data["feedbacks"] = DB::select( DB::raw("SELECT * FROM `tbl_topic_feedback` left join `tbl_feedback_question` on `tbl_feedback_question`.`feedback_question_id` = `tbl_topic_feedback`.`feedback_question_id_fk` WHERE ".$conditionTopic." AND `tbl_feedback_question`.`feedback_type` = 'topic';") );
        $data["generalFeedbacks"] = DB::select( DB::raw("SELECT * FROM `tbl_topic_feedback` left join `tbl_feedback_question` on `tbl_feedback_question`.`feedback_question_id` = `tbl_topic_feedback`.`feedback_question_id_fk` WHERE ".$conditionGeneral." AND `tbl_feedback_question`.`feedback_type` = 'general';") );

        return response()->json(array('data'=> $data), 200);
    }
}
