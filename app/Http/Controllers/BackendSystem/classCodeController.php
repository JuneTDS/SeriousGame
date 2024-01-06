<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class classCodeController extends Controller
{
    public function showClassCodesDashboard(Request $request)
    {
        // Retrieve the subject_id and subject_name columns from tbl_subject
        $subjects = DB::table('tbl_subject')->select('subject_id', 'subject_name')->get();

        $searchKeyword = $request->input('classCode');
        $selectedSubject = $request->input('subjectName');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $sortBy = $request->input('sortBy');
        $sortColumn = $request->input('sortColumn');

        // Retrieve data from the tbl_class_code table
        $query = DB::table('tbl_class_code')
        ->join('tbl_subject', 'tbl_class_code.subject_id_fk', '=', 'tbl_subject.subject_id')
        ->join('tbl_subject_class', 'tbl_class_code.subject_class_id_fk', '=', 'tbl_subject_class.subject_class_id')
        ->select('tbl_class_code.*', 'tbl_subject.subject_name', 'tbl_subject_class.class_name');

        if (!empty($searchKeyword) || !empty($selectedSubject) || !empty($startDate) || !empty($endDate)){
            if (!empty($searchKeyword)){
                // $query->where(function ($query) use ($searchKeyword) {
                //     $query->where('tbl_class_code.class_code', 'like', '%' . $searchKeyword . '%')
                //         ->orWhere('tbl_class_code.subject_class_id_fk', 'like', '%' . $searchKeyword . '%');
                // });

                $query->where(function ($query) use ($searchKeyword) {
                    $query->where(function ($query) use ($searchKeyword) {
                        $query->where('tbl_class_code.class_code', 'like', '%' . $searchKeyword . '%')
                            ->orWhere('tbl_subject_class.class_name', 'like', '%' . $searchKeyword . '%');
                    })->orWhere('tbl_class_code.subject_class_id_fk', '=', 'tbl_subject_class.subject_class_id');
                });                
            }

            if ($selectedSubject !== 'All') {
                $query->where('tbl_class_code.subject_id_fk', $selectedSubject);
            }

            if (!empty($startDate)) {
                // Use the DATE() function to extract the date part from the start_date column
                $startDate = date('Y-m-d', strtotime($startDate));
                // Query the user data based on the range of timestamps
                $query->whereDate('tbl_class_code.start_date', '=', $startDate);
            }

            if (!empty($endDate)) {
                // Use the DATE() function to extract the date part from the start_date column
                $endDate = date('Y-m-d', strtotime($endDate));
                // Query the user data based on the range of timestamps
                $query->whereDate('tbl_class_code.end_date', '=', $endDate);
            }
        }

        // Check if $sortBy and $sortColumn are not empty and not null
        if (!empty($sortBy) && !empty($sortColumn)) {
            // Validate $sortBy as a valid sorting direction
            if ($sortBy === 'asc' || $sortBy === 'desc') {
                // Use $sortBy and $sortColumn in the orderBy clause
                $query->orderBy($sortColumn, $sortBy);
            } else {
                // Default to ascending sorting if $sortBy is not valid
                $query->orderBy($sortColumn, 'asc');
            }
        } else {
            // Default sorting if $sortBy or $sortColumn are empty or null
            $query->orderBy('class_code_id', 'asc');
        }

        $classCodes = $query
        ->get();

        return view('backendSystem.classCode.classCodeDashboard',[
            'subjects' => $subjects,
            'classCodes' => $classCodes,
            'searchKeyword' => $searchKeyword,
            'selectedSubject' => $selectedSubject,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function createClassCode(Request $request)
    {

        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Perform server-side validation
        // $validatedData = $request->validate([
        //     'classCode' => 'required',
        //     'subject' => 'required',
        //     'subject_Class' => 'required',
        //     'classSize' => 'required',
        //     'startDate' => 'required',
        //     'endDate' => 'required',
        // ]);

        // Extract data from the JSON request
        $classCode = $data['classCode'];
        $subject = $data['subject'];
        $class = $data['subject_Class'];
        $classSize = $data['classSize'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        if ($classCode == "") {
            return response()->json(['message' => "Class code is required."]);
        }
        if ($subject == "") {
            return response()->json(['message' => "Subject is required."]);
        }
        if ($class == "") {
            return response()->json(['message' => "Class is required."]);
        }
        if ($classSize == "") {
            return response()->json(['message' => "ClassSize is required."]);
        }
        if ($startDate == "") {
            return response()->json(['message' => "Start date is required."]);
        }
        if ($endDate == "") {
            return response()->json(['message' => "End date is required."]);
        }

        $isClassCodeExist = DB::table('tbl_class_code')->where('class_code', $classCode)->get();

        if (count($isClassCodeExist) > 0) {
            return response()->json(['message' => "Class code is exist."]);
        }
        
        // Format startDate and endDate with the current time
        $startDate = date("Y-m-d H:i:s", strtotime($startDate));
        $endDate = date("Y-m-d H:i:s", strtotime($endDate));

        $classCodeData = DB::select( DB::raw("INSERT INTO `tbl_class_code`(`class_code`, `subject_id_fk`, `subject_class_id_fk`, `class_size`, `start_date`, `end_date`) VALUES ('$classCode','$subject','$class','$classSize', '$startDate', '$endDate')") );

        return response()->json(['success' => true]);
    }


    public function showClassCodeInfo($id)
    {
        // Retrieve data from the tbl_class_code table
        $classCodeData = DB::table('tbl_class_code')
        ->join('tbl_subject', 'tbl_class_code.subject_id_fk', '=', 'tbl_subject.subject_id')
        ->join('tbl_subject_class', 'tbl_class_code.subject_class_id_fk', '=', 'tbl_subject_class.subject_class_id')
        ->select('tbl_class_code.*', 'tbl_subject.subject_name', 'tbl_subject_class.class_name')
        ->where('tbl_class_code.class_code_id', '=', $id)
        ->first();

        return view('backendSystem.classCode.classCodeInfo',[
            'classCodeData' => $classCodeData,
        ]);
    }

    public function showClassCodeEdit($id)
    {
        $subjects = DB::table('tbl_subject')->select('subject_id', 'subject_name')->get();

        // Retrieve data from the tbl_class_code table
        $classCodeData = DB::table('tbl_class_code')
        ->join('tbl_subject', 'tbl_class_code.subject_id_fk', '=', 'tbl_subject.subject_id')
        ->join('tbl_subject_class', 'tbl_class_code.subject_class_id_fk', '=', 'tbl_subject_class.subject_class_id')
        ->select('tbl_class_code.*', 'tbl_subject.subject_name', 'tbl_subject_class.class_name')
        ->where('tbl_class_code.class_code_id', '=', $id)
        ->first();

        // Step 1: Retrieve the subject_id_fk for the given class_code_id
        $subjectIdFk = DB::table('tbl_class_code')
        ->where('class_code_id', $id)
        ->value('subject_id_fk');

        // Step 2: Retrieve subject_class_id and class_name based on the subject_id_fk
        $subjectClassData = DB::table('tbl_subject_class')
            ->where('subject_id_fk', $subjectIdFk)
            ->select('subject_class_id', 'class_name')
            ->get();

        return view('backendSystem.classCode.classCodeEdit',[
            'subjects' => $subjects,
            'subjectClassData' => $subjectClassData,
            'classCodeData' => $classCodeData,
        ]);
    }

    public function classCodeEditSave(Request $request)
    {
        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Perform server-side validation
        $validatedData = $request->validate([
            'classCode' => 'required',
            'subject' => 'required',
            'subject_Class' => 'required',
            'classSize' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
        ]);

        // Extract data from the JSON request
        $classCodeId = $data['classCodeId'];
        $classCode = $data['classCode'];
        $subject = $data['subject'];
        $subject_Class = $data['subject_Class'];
        $classSize = $data['classSize'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        // Format startDate and endDate with the current time
        $startDate = date("Y-m-d H:i:s", strtotime($startDate));
        $endDate = date("Y-m-d H:i:s", strtotime($endDate));

        $classCodeDataSql = "
        UPDATE tbl_class_code
        SET class_code = '$classCode',
            subject_id_fk = '$subject',
            subject_class_id_fk = '$subject_Class',
            class_size = $classSize,
            start_date = '$startDate',
            end_date = '$endDate'
        WHERE class_code_id = $classCodeId
    ";    

        $classCodeData = DB::update($classCodeDataSql);

        return response()->json(['success' => true]);
    }

    public function deleteClassCode($id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Delete the related records
            DB::table('tbl_class_code')->where('class_code_id', $id)->delete();

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // If an error occurs during deletion, roll back the transaction
            DB::rollback();

            return response()->json(['success' => false]);
        }
    }

    public function getSubjectClasses(Request $request, $id) {
        // Fetch classes based on the subject ID
        $subjectClasses = DB::table('tbl_subject_class')
            ->where('subject_id_fk', $id)
            ->pluck('class_name', 'subject_class_id');
    
        return response()->json(['success' => true, 'data' => $subjectClasses]);
    }    
}