<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB; // Import the DB facade

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
            'className' => 'required|unique:tbl_subject_class,className',
            'academicYear' => 'required',
            'academicSemester' => 'required|in:1,2',
            'lecturerId' => 'required',
            'subjectId' => 'required',
        ]);

        // Extract data from the JSON request
        $className = $data['className'];
        $academicYear = $data['emaacademicYearil'];
        $academicSemester = $data['academicSemester'];
        $lecturerId = $data['lecturerId'];
        $subjectId = $data['subjectId'];

        $updatedAt = Carbon::now()->timestamp;
        $createdAt = Carbon::now()->timestamp;
        $userId = Auth::user()->id;

        $subjectClassData       = DB::select( DB::raw("INSERT INTO `tbl_subject_class`(`class_name`, `academic_year`, `academic_semester`, `subject_id_fk`, `lecturer_in_charge_id_fk`, `active_flag`, `updated_at`, `updated_by`, `created_at`, `created_by`) VALUES ('$className','$academicYear','$academicSemester','$subjectId', $lecturerId, '0', $updatedAt, $userId, $createdAt, $userId )") );

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
            'class_Update' => 'required|unique:tbl_subject_class,className',
            'year_Update' => 'required',
            'sem_Update' => 'required|in:1,2',
            'lecturer_Update' => 'required',
            'subject_Update' => 'required',
        ]);

        // Extract data from the JSON request
        $class_Update = $data['class_Update'];
        $year_Update = $data['year_Update'];
        $sem_Update = $data['sem_Update'];
        $lecturer_Update = $data['lecturer_Update'];
        $subject_Update = $data['subject_Update'];

        $updatedAt = Carbon::now()->timestamp;
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
                // Calculate the Unix timestamps for the start and end of the day
                $startOfDay = strtotime($selectedDate . ' 00:00:00'); // First second of the day
                $endOfDay = strtotime($selectedDate . ' 23:59:59');   // Last second of the day
                // Query the user data based on the range of timestamps
                $query->whereBetween('tbl_subject_class_enrolment.updated_at', [$startOfDay, $endOfDay]);
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
        $uploadedFile = $request->file('fileUpload');

        if ($uploadedFile) {
            $filePath = $uploadedFile->getRealPath();

            // Use the league/csv package to parse the CSV file
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0); // Assuming the first row is the header row

            $data = $csv->getRecords();

            $enrolStudentsResult = [];
            foreach ($data as $row) {
                // Get the class_id_fk value from the CSV
                $classIdFk = $row['class_id_fk'];

                // Look up the class_name based on class_id_fk in your database
                $className = DB::table('tbl_subject_class')
                    ->where('subject_class_id', $classIdFk)
                    ->value('class_name');

                // Get the student_full_name from the CSV
                $studentFullName = $row['student_full_name'];

                // Add the data to the results array
                $enrolStudentsResult[] = [
                    'class_name' => $className,
                    'student_full_name' => $studentFullName,
                ];
            }

            // Pass the results to your view
            return view('backendSystem.lectureClasses.enrolStudentDashboard',[
                'enrolStudentsResult' => $enrolStudentsResult,
            ]);
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
