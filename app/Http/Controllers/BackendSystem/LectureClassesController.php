<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LectureClassesController extends Controller
{
    public function showLectureClassesDashboard(Request $request)
    {
        //To have a list of year for acedemic year dropdown in create form and edit form
        $yearList = [];
        $currentYear = date('Y'); // Get the current year
        $numberOfYearsToShow = 5; // Number of years to show in the dropdown

        for ($i = -5; $i < $numberOfYearsToShow; $i++) {
            $year = $currentYear + $i;
            $yearList[$year] = $year;
        }

        //To get all the user under 'Lecturer', 'Lecturer_Content_Creator', 'Lecturer_Manager' role
        $lecturersData = DB::table('tbl_auth_assignment')
            ->whereIn('item_name', ['Lecturer', 'Lecturer_Content_Creator', 'Lecturer_Manager'])
            ->join('tbl_user', 'tbl_auth_assignment.user_id', '=', 'tbl_user.id')
            ->select('tbl_auth_assignment.user_id', 'tbl_user.username')
            ->get();

        //To have a list of subject for subject dropdown in create form and edit form
        $subjectsList = DB::table('tbl_subject')
            ->select('subject_id', 'subject_name')
            ->get();

        // Fetch unique academic years from tbl_subject_class
        $academicYears = DB::table('tbl_subject_class')
        ->distinct()
        ->pluck('academic_year');

        // Fetch unique academic semesters from tbl_subject_class
        $academicSemesters = DB::table('tbl_subject_class')
        ->distinct()
        ->pluck('academic_semester');
        
        $searchKeyword = $request->input('classname');
        $selectedAcademicYear = $request->input('academicYear');
        $selectedAcademicSemester = $request->input('academicSemester');
        $sortBy = $request->input('sortBy');
        $sortColumn = $request->input('sortColumn');

        // Query to retrieve data from tbl_subject_class and join with tbl_subject and tbl_user
        $query = DB::table('tbl_subject_class')
            ->select('tbl_subject_class.*', 'tbl_subject.subject_name', 'tbl_user.username')
            ->join('tbl_subject', 'tbl_subject_class.subject_id_fk', '=', 'tbl_subject.subject_id')
            ->join('tbl_user', 'tbl_subject_class.lecturer_in_charge_id_fk', '=', 'tbl_user.id');

        if (!empty($searchKeyword) || !empty($selectedAcademicYear) || !empty($selectedAcademicSemester)){
            if (!empty($searchKeyword)){
                $query->where(function ($query) use ($searchKeyword) {
                    $query->where('tbl_subject_class.class_name', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('tbl_subject.subject_name', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('tbl_user.username', 'like', '%' . $searchKeyword . '%');
                });
            }

            if ($selectedAcademicYear !== 'All') {
                $query->where('tbl_subject_class.academic_year', $selectedAcademicYear);
            }

            if ($selectedAcademicSemester !== 'All') {
                $query->where('tbl_subject_class.academic_semester', $selectedAcademicSemester);
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
            $query->orderBy('subject_class_id', 'asc');
        }

        $lectureClasses = $query->get();

        return view('backendSystem.lectureClasses.lectureClassesDashboard', [
            'lectureClasses' => $lectureClasses,
            'yearList' => $yearList,
            'lecturersData' => $lecturersData,
            'subjectsList' => $subjectsList,
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $selectedAcademicYear,
            'academicSemesters' => $academicSemesters,
            'selectedAcademicSemester' => $selectedAcademicSemester,
            'searchKeyword' => $searchKeyword,
        ]);
    }

    // Function to create a new user
    public function createLectureClass(Request $request)
    {
        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Perform server-side validation
        $validatedData = $request->validate([
            'createClassName' => 'required|unique:tbl_subject_class,class_name',
            'createAcademicYear' => 'required',
            'createAcademicSemester' => 'required|in:1,2',
            'createLecturerId' => 'required',
            'createSubjectId' => 'required',
        ]);

        // Extract data from the JSON request
        $className = $data['createClassName'];
        $academicYear = $data['createAcademicYear'];
        $academicSemester = $data['createAcademicSemester'];
        $lecturerId = $data['createLecturerId'];
        $subjectId = $data['createSubjectId'];

        $updatedAt = now()->toDateTimeString();
        $createdAt = now()->toDateTimeString();
        $userId = Auth::user()->id;

        // $subjectClassData       = DB::select( DB::raw("INSERT INTO `tbl_subject_class`(`class_name`, `academic_year`, `academic_semester`, `subject_id_fk`, `lecturer_in_charge_id_fk`, `active_flag`, `updated_at`, `updated_by`, `created_at`, `created_by`) VALUES ('$className','$academicYear','$academicSemester','$subjectId', $lecturerId, '0', $updatedAt, $userId, $createdAt, $userId )") );
        $subjectClassData = DB::table('tbl_subject_class')->insert([
            'class_name' => $className,
            'academic_year' => $academicYear,
            'academic_semester' => $academicSemester,
            'subject_id_fk' => $subjectId,
            'lecturer_in_charge_id_fk' => $lecturerId,
            'active_flag' => 0,
            'updated_at' => $updatedAt,
            'updated_by' => $userId,
            'created_at' => $createdAt,
            'created_by' => $userId,
        ]);        

        return response()->json(['success' => true]);
    }

    public function showLectureClassInfo($id)
    {
        //To have a list of year for acedemic year dropdown in create form and edit form
        $yearList = [];
        $currentYear = date('Y'); // Get the current year
        $numberOfYearsToShow = 5; // Number of years to show in the dropdown

        for ($i = -5; $i < $numberOfYearsToShow; $i++) {
            $year = $currentYear + $i;
            $yearList[$year] = $year;
        }

        //To get all the user under 'Lecturer', 'Lecturer_Content_Creator', 'Lecturer_Manager' role
        $lecturersData = DB::table('tbl_auth_assignment')
            ->whereIn('item_name', ['Lecturer', 'Lecturer_Content_Creator', 'Lecturer_Manager'])
            ->join('tbl_user', 'tbl_auth_assignment.user_id', '=', 'tbl_user.id')
            ->select('tbl_auth_assignment.user_id', 'tbl_user.username')
            ->get();

        //To have a list of subject for subject dropdown in create form and edit form
        $subjectsList = DB::table('tbl_subject')
            ->select('subject_id', 'subject_name')
            ->get();

        $lectureClassData = DB::table('tbl_subject_class')
            ->select('tbl_subject_class.*', 'tbl_subject.subject_name', 'tbl_user.username AS lecturer_username', 'tbl_user1.username AS updated_by_username', 'tbl_user2.username AS created_by_username')
            ->join('tbl_subject', 'tbl_subject_class.subject_id_fk', '=', 'tbl_subject.subject_id')
            ->join('tbl_user AS tbl_user', 'tbl_subject_class.lecturer_in_charge_id_fk', '=', 'tbl_user.id')
            ->leftJoin('tbl_user AS tbl_user1', 'tbl_subject_class.updated_by', '=', 'tbl_user1.id')
            ->leftJoin('tbl_user AS tbl_user2', 'tbl_subject_class.created_by', '=', 'tbl_user2.id')
            ->where('tbl_subject_class.subject_class_id', '=', $id)
            ->first();

        return view('backendSystem.lectureClasses.lectureClassInfo',[
            'yearList' => $yearList,
            'lecturersData' => $lecturersData,
            'subjectsList' => $subjectsList,
            'lectureClassData' => $lectureClassData
        ]);
    }

    public function lectureClassEditSave(Request $request)
    {
        // Retrieve data from the JSON request
        $data = $request->json()->all();

        // Perform server-side validation
        $validatedData = $request->validate([
            // 'class_Update' => 'required|unique:tbl_subject_class,class_name',
            'class_Update' => 'required',
            'year_Update' => 'required',
            'sem_Update' => 'required|in:1,2',
            'lecturer_Update' => 'required',
            'subject_Update' => 'required',
        ]);

        // Extract data from the JSON request
        $subjectClass_Update = $data['subjectClass_Update'];
        $class_Update = $data['class_Update'];
        $year_Update = $data['year_Update'];
        $sem_Update = $data['sem_Update'];
        $lecturer_Update = $data['lecturer_Update'];
        $subject_Update = $data['subject_Update'];

        $updatedAt = now()->toDateTimeString();
        $userId = Auth::user()->id;

        $lectureClassSql = "
        UPDATE tbl_subject_class
        SET class_name = '$class_Update',
            academic_year = '$year_Update',
            academic_semester = '$sem_Update',
            subject_id_fk = '$subject_Update',
            lecturer_in_charge_id_fk = '$lecturer_Update',
            updated_at = '$updatedAt',
            updated_by = '$userId'
        WHERE subject_class_id = $subjectClass_Update
        ";

        // Execute the raw SQL query to update the record
        $lectureClassData = DB::update($lectureClassSql);

        return response()->json(['success' => true]);
    }

    public function showManageStudentDashboard(Request $request, $id)
    {
        $searchKeyword = $request->input('studentName');
        $selectedDate = $request->input('updatedOn');

        // Retrieve data from tbl_subject_class_enrolment
        $query = DB::table('tbl_subject_class_enrolment')
            ->select(
                'tbl_subject_class.subject_class_id',
                'tbl_subject_class_enrolment.subject_class_enrolment_id',
                'tbl_subject_class.class_name',
                'tbl_subject.subject_name',
                'tbl_user.username',
                'tbl_subject_class_enrolment.updated_at'
            )
            ->join('tbl_subject_class', 'tbl_subject_class_enrolment.subject_class_id_fk', '=', 'tbl_subject_class.subject_class_id')
            ->join('tbl_subject', 'tbl_subject_class.subject_id_fk', '=', 'tbl_subject.subject_id')
            ->join('tbl_user', 'tbl_subject_class_enrolment.user_id_fk', '=', 'tbl_user.id')
            ->where('tbl_subject_class_enrolment.subject_class_id_fk', '=', $id);

        if (!empty($searchKeyword) || !empty($selectedDate)){
            if (!empty($searchKeyword)){
                $query->where(function ($query) use ($searchKeyword) {
                    $query->where('tbl_user.username', 'like', '%' . $searchKeyword . '%');
                });
            }

            if (!empty($selectedDate)) {
                // // Calculate the Unix timestamps for the start and end of the day
                // $startOfDay = strtotime($selectedDate . ' 00:00:00'); // First second of the day
                // $endOfDay = strtotime($selectedDate . ' 23:59:59');   // Last second of the day
                // // Query the user data based on the range of timestamps
                // $query->whereBetween('tbl_subject_class_enrolment.updated_at', [$startOfDay, $endOfDay]);

                // Use the DATE() function to extract the date part from the start_date column
                $selectedDate = date('Y-m-d', strtotime($selectedDate));
                // Query the user data based on the range of timestamps
                $query->whereDate('tbl_subject_class_enrolment.updated_at', '=', $selectedDate);
            }
        }
        
        $manageStudentsData = $query
        ->get();

        // Modify the query to retrieve a single record based on the provided ID
        $lectureClassId = $id;

        return view('backendSystem.lectureClasses.manageStudentDashboard', [
            'manageStudentsData' => $manageStudentsData,
            'lectureClassId' => $lectureClassId,
            'searchKeyword' => $searchKeyword,
            'selectedDate' => $selectedDate,
        ]);
    }

    public function showEnrolStudentDashboard($id)
    {
        $lectureClassId = $id;

        $enrolStudentsResult = [];

        return view('backendSystem.lectureClasses.enrolStudentDashboard', [
            'enrolStudentsResult' => $enrolStudentsResult,
            'lectureClassId' => $lectureClassId,
        ]);
    }

    public function downloadEnrolStudentTemplate()
    {
        // Define the path to the file you want to download
        $filePath = public_path('enrol_Student_Template.csv');

        // Check if the file exists
        if (file_exists($filePath)) {
            // Provide a custom file name for the downloaded file
            $fileName = 'enrol_Student_Template.csv';

            // Set the headers for the response
            $headers = [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];

            // Return the file as a download response
            return Response::download($filePath, $fileName, $headers);
        } else {
            // Handle the case where the file does not exist
            abort(404, 'File not found');
        }
    }

    public function showUploadForm()
    {
        return view('upload');
    }

    public function uploadEnrolStudentFile(Request $request)
    {
        $uploadedFile = $request->file('file');

        if ($request->hasFile('file')) {

            $uploadedFile = $request->file('file');

            // Get the path to the uploaded file
            $filePath = $request->file('file')->getRealPath();

            // Read the CSV file into an array
            $csv = array_map('str_getcsv', file($filePath));

            // Extract the header row
            $uploadedHeader = $csv[0]; // This is your header row

            // Calculate the row count, excluding the header row
            $rowCount = count($csv) - 1;

            for ($i = 1; $i <= $rowCount; $i++) {
                $classArray = DB::table('tbl_subject_class')
                ->select('*')
                ->where('subject_class_id', $csv[$i][0])
                ->first();

                $newClassFlag = (!$classArray->has('subject_class_id')) ? true : false;

                if($newClassFlag){
                    $resultArray[$i]['class'] = "Class Id " . $csv[$i][0] . " cannot be found. Kindly create the class before enrolling";

                    $studentArray = DB::table('tbl_user')
                    ->where('username', $csv[$i][2])
                    ->first();

                    $studentFlag = isset($studentArray);

                    if (!$studentFlag) {
                        $resultArray[$i]['student'] = '"' . $csv[$i][1] . '" account is not found in the system. Kindly create the class first and re-attempt to enroll.';
                    }
                    else {
                        $resultArray[$i]['student'] = '"' . $csv[$i][1] . '" account exists in the system. Kindly create the class first and re-attempt to enroll.';
                    }
                } 
                elseif (!$newClassFlag) {
                    if ($studentArray->status != 1) {
                        $resultArray[$i]['student'] = $csv[$i][1] . " account is not active. Kindly check with the administrator for student status";
                    }
                    elseif ($studentArray->status == 1) {
                        $studentClassEnrolStatus = DB::table('tbl_subject_class_enrolment')
                        ->where('user_id_fk', $studentArray->id)
                        ->where('subject_class_id_fk', $csv[$i][0])
                        ->first();

                        $classEnrolledFlag = isset($studentClassEnrolStatus);

                        if ($classEnrolledFlag) {
                            // Student is already enrolled, set an appropriate message
                            $resultArray[$i]['student'] = $csv[$i][1] . " is already enrolled in " . $classArray->class_name . " thus no action is taken";
                        }
                        else {
                            $uploadClassDetails = DB::table('tbl_subject_class')
                            ->where('subject_class_id', $csv[$i][0])
                            ->first();

                            $similarClassList = DB::table('tbl_subject_class')
                            ->where('subject_id_fk', $uploadClassDetails->subject_id_fk)
                            ->where('academic_year', $uploadClassDetails->academic_year)
                            ->where('academic_semester', $uploadClassDetails->academic_semester)
                            ->get();

                            $similarClassFlag = (count($similarClassList) >= 2);

                            if ($similarClassFlag) {
                                $classList = [];

                                foreach ($similarClassList as $class) {
                                    $classList[] = $class->subject_class_id;
                                }

                                $differentClassEnroled = DB::table('tbl_subject_class_enrolment')
                                ->join('tbl_subject_class', 'tbl_subject_class.subject_class_id', '=', 'tbl_subject_class_enrolment.subject_class_id_fk')
                                ->whereIn('subject_class_id_fk', $classList)
                                ->where('user_id_fk', $studentArray->id)
                                ->get();

                                $differentClassCount = count($differentClassEnroled);

                                if ($differentClassCount >= 1) {
                                    $differentClassNameList = "";
                                    
                                    $resultArray = [];

                                    foreach ($differentClassEnroled as $differentClass) {
                                        $name = DB::table('tbl_subject_class')
                                            ->where('subject_class_id', $differentClass->subject_class_id_fk)
                                            ->first();

                                        $differentClassNameList = $name->class_name . ",";
                                    }

                                    $differentClassNameList = rtrim($differentClassNameList, ',');

                                    $resultArray[$i]['student'] = $csv[$i][1] . " is already enrolled in " . $differentClassNameList . " thus no action is taken";
                                }
                                else{
                                    DB::table('tbl_subject_class_enrolment')->insert([
                                        'subject_class_id_fk' => $csv[$i][0],
                                        'user_id_fk' => $studentArray->id,
                                        'updated_at' => now(),
                                        'updated_by' => auth()->user()->id,
                                        'created_at' => now(),
                                        'created_by' => auth()->user()->id
                                    ]);

                                    $resultArray[$i]['student'] =  $csv[$i][1] . " is being enrolled in " . $classArray['class_name'];
                                }
                            }
                            else{
                                DB::table('tbl_subject_class_enrolment')->insert([
                                    'subject_class_id_fk' => $csv[$i][0],
                                    'user_id_fk' => $studentArray->id,
                                    'updated_at' => now(),
                                    'updated_by' => auth()->user()->id,
                                    'created_at' => now(),
                                    'created_by' => auth()->user()->id
                                ]);

                                $resultArray[$i]['student'] =  $csv[$i][1] . " is being enrolled in " . $classArray['class_name'];
                            }
                        }
                    }                    
                }
            }
        }

        // Handle the case where no file was uploaded
        return redirect()->back()->with('error', 'No file uploaded.');
    }

    public function deleteLectureClass($id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Delete the user and related records
            DB::table('tbl_subject_class')->where('subject_class_id', $id)->delete();

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // If an error occurs during deletion, roll back the transaction
            DB::rollback();

            return response()->json(['success' => false]);
        }
    }
}
