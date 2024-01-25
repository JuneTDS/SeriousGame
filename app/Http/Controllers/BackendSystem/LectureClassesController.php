<?php

namespace App\Http\Controllers\BackendSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        $classCode = DB::table('tbl_class_code')
            ->where('subject_class_id_fk', $lectureClassId)
            ->exists();

        return view('backendSystem.lectureClasses.manageStudentDashboard', [
            'manageStudentsData' => $manageStudentsData,
            'lectureClassId' => $lectureClassId,
            'searchKeyword' => $searchKeyword,
            'selectedDate' => $selectedDate,
            'classCode' => $classCode
        ]);
    }

    public function showEnrolStudentDashboard($id)
    {
        $lectureClassId = $id;

        $users = DB::table('tbl_user')->where('status', 1)->get();

        $aldEnrollUsers = DB::table('tbl_subject_class_enrolment')
            ->select('user_id_fk')
            ->where('subject_class_id_fk', $lectureClassId)
            ->pluck('user_id_fk')
            ->toArray();

        return view('backendSystem.lectureClasses.enrolStudentDashboard', [
            'lectureClassId' => $lectureClassId,
            'users' => $users,
            'aldEnrollUsers' => $aldEnrollUsers
        ]);
    }

    public function uploadEnrolStudentFile(Request $request)
    {
        $lectureClassId = $request->input('lectureClassId');

        $class_name = DB::table('tbl_subject_class')
        ->where('subject_class_id', $lectureClassId)
        ->value('class_name');
        
        $csvData = []; // Initialize an empty array

        // Check if a file was uploaded
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');

            if ($uploadedFile->getClientOriginalExtension() === 'csv') {
                $csv = Reader::createFromPath($uploadedFile->getRealPath(), 'r');
                $csv->setHeaderOffset(0); // Skip the first row (header)

                // Check if the class code for the subject class exists in the database
                $classCodeExists = DB::table('tbl_class_code')
                ->where('subject_class_id_fk', $lectureClassId)
                ->exists();

                $totalUsersToEnroll = 0;
                $remainingClassSize = 0;
                if ($classCodeExists){

                    //Check start date and end dat of the class (Start)
                    // Get the start_date and end_date for the specified subject class
                    $classInfo = DB::table('tbl_class_code')
                        ->select('start_date', 'end_date')
                        ->where('subject_class_id_fk', $lectureClassId)
                        ->first();

                    if ($classInfo) {
                        $startDate = Carbon::parse($classInfo->start_date);
                        $endDate = Carbon::parse($classInfo->end_date);
                        $currentDate = Carbon::now();

                        // Check if the current date is between the start and end date
                        if ($currentDate->between($startDate, $endDate)) {
                            //Check the number of user to enrol is less than remaing position of class size(Start)
                            //Get the number of user in CSV
                            $userCountCSV = $csv->count(); 

                            //Get the ID of user in CSV that already exist in the database
                            $userIdsInDatabase = [];
                            foreach ($csv as $row) {
                                $email = $row['email'];
                                $user = DB::table('tbl_user')
                                    ->select('id')
                                    ->where('email', $email)
                                    ->first();
                            
                                if ($user) {
                                    $userIdsInDatabase[] = $user->id;
                                }
                            }

                            // Count how many users from the CSV already exist in the database
                            $userIdsInDatabaseCount = count($userIdsInDatabase);

                            // Calculate the number of users that need to be created
                            $usersToCreateCount = $userCountCSV - $userIdsInDatabaseCount;

                            //Get all user ID that already enrol
                            $enrolledUserIds = DB::table('tbl_subject_class_enrolment')
                            ->select('user_id_fk')
                            ->where('subject_class_id_fk', $lectureClassId)
                            ->pluck('user_id_fk')
                            ->toArray();

                            // Count number of users already enrol
                            $enrolledUserCount = count($enrolledUserIds);

                            //Get the ID of user in CSV that already exist in the databas and yet to enrol
                            $newUserIdsToEnroll = array_diff($userIdsInDatabase, $enrolledUserIds);
                            
                            // Count the number of new users to enroll
                            $newUserIdsToEnrollCount = count($newUserIdsToEnroll);

                            //Get the class size of the subject class
                            $classSize = DB::table('tbl_class_code')
                            ->where('subject_class_id_fk', $lectureClassId)
                            ->value('class_size');

                            //Check number of user in the class
                            $usersInClassCount = DB::table('tbl_class_code')
                            ->where('subject_class_id_fk', $lectureClassId)
                            ->count();

                            // Calculate the remaining class size
                            $remainingClassSize = $classSize - $usersInClassCount;

                            //Calculate total user to create and enrol
                            $totalUsersToEnroll = $usersToCreateCount + $newUserIdsToEnrollCount;
                            //Check the number of user to enrol is less than remaing position of class size or not (End)
                        } else {
                            // The current date is not within the date range, return an alert message.
                            // return redirect()->back()->with('error', 'Cannot enroll: The current date is not within the specified date range.');
                        }
                    } else {
                        // Handle the case where the specified $lectureClassId is not found.
                        // return redirect()->back()->with('error', 'Class not found with the specified ID.');
                    }
                    //Check start date and end date of the class (End)
                } else {
                    // Default values for totalUsersToEnroll and remainingClassSize
                    $totalUsersToEnroll = 0;
                    $remainingClassSize = 0;
                }

                if ($totalUsersToEnroll <= $remainingClassSize) {
                    
                    //Create user in the CSV that is not exist in the database (Start)
                    foreach ($csv as $row) {
                        $email = $row['email']; // Email from the CSV
                        // Check if the email does not exist in the tbl_user table
                        if (DB::table('tbl_user')->where('email', $email)->exists()) {
                            $username = $row['username'];
                            $password = $row['password'];
                            $passwordHash = Hash::make($password);

                            // Generate a random auth key
                            $authKey = Str::random(32);

                            // Set other fields
                            $status = 1;
                            $createdAt = Carbon::now()->timestamp;

                            // Insert user data into tbl_user
                            DB::table('tbl_user')->insert([
                                'username' => $username,
                                'email' => $email,
                                'auth_key' => $authKey,
                                'password_hash' => $passwordHash,
                                'status' => $status,
                                'first_login' => 'Yes',
                                'created_at' => $createdAt,
                                'updated_at' => $createdAt,
                            ]);

                            $user = DB::table('tbl_user')
                                ->where('email', $email)
                                ->first();

                            // Insert user profile into tbl_user_profile using the query builder
                            DB::table('tbl_user_profile')->insert([
                                'user_id' => $user->id,
                                'full_name' => $username,
                                'email_gravatar' => $email,
                                'admin_no' => ' ',
                                'created_at' => $createdAt,
                                'updated_at' => $createdAt,
                            ]);

                            if (!DB::table('tbl_auth_assignment')->where('item_name', 'user')->where('user_id', $user->id)->exists()) {
                                // Insert user role into tbl_auth_assignment using the query builder
                                DB::table('tbl_auth_assignment')->insert([
                                    'item_name' => 'user',
                                    'user_id' => $user->id,
                                    'created_at' => $createdAt,
                                ]);
                            }

                            // Add the row to $csvData
                            // $csvData[] = [
                            //     'username' => $username,
                            //     'email' => $email,
                            //     'password' => $password,
                            //     'class_name' => $class_name,
                            // ];
                            array_push($csvData, [
                                'username' => $username,
                                'email' => $email,
                                'password' => $password,
                                'class_name' => $class_name,
                            ]);
                        }
                    }
                    //Create user in the CSV that is not exist in the database (End)

                    //Enrol student (Start)
                    // Get the ID in tbl_user where the email in the CSV matches the email in tbl_user.
                    $userIdsToEnroll = [];
                    foreach ($csv as $row) {
                        $email = $row['email'];
                        $user = DB::table('tbl_user')
                            ->select('id')
                            ->where('email', $email)
                            ->first();

                        if ($user) {
                            $userIdsToEnroll[] = $user->id;
                        }
                    }

                    //Find all the user_id_fk in tbl_subject_class_enrolment where the subject_class_id_fk matches $lectureClassId.
                    $existingUserIds = DB::table('tbl_subject_class_enrolment')
                    ->select('user_id_fk')
                    ->where('subject_class_id_fk', $lectureClassId)
                    ->pluck('user_id_fk')
                    ->toArray();

                    //  Insert the IDs from userIdsToEnroll that do not exist in existingUserIds into enrolment table.
                    $currentUserId = auth()->user()->id; // Get the current login user ID
                    $createUpdated_Time = now()->toDateTimeString();
                    
                    $newUserIdsToEnroll = array_diff($userIdsToEnroll, $existingUserIds);
                    foreach ($newUserIdsToEnroll as $userIdToEnroll) {
                        DB::table('tbl_subject_class_enrolment')->insert([
                            'subject_class_id_fk' => $lectureClassId,
                            'user_id_fk' => $userIdToEnroll,
                            'updated_at' => $createUpdated_Time,
                            'created_at' => $createUpdated_Time,
                            'updated_by' => $currentUserId,
                            'created_by' => $currentUserId,
                        ]);
                    }
                    //Enrol student (End)

                    // Show result at table
                    return view('backendSystem.lectureClasses.enrolStudentDashboard', [
                        'lectureClassId' => $lectureClassId,
                        'csvData' => $csvData,
                    ]);
                } else {
                    return view('backendSystem.lectureClasses.enrolStudentDashboard', [
                        'lectureClassId' => $lectureClassId,
                        'csvData' => $csvData,
                    ]);
                }
            } else {
                // return redirect()->back()->with('error', 'The uploaded file is not a CSV.');
            }
        } else {
            // return redirect()->back()->with('error', 'No file was uploaded.');
        }
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

    public function uploadEnrolStudent(Request $request)
    {
        $lectureClassId = $request->input('lectureClassId');
        $userId = $request->input('student');

        $result = $this->enrolStudent($lectureClassId, $userId);

        if ($result) {
            return redirect()->back()->with('message', 'Enrol was success.');
        } 
        else {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function enrolStudent($lectureClassId, $userId)
    {
        $class_name = DB::table('tbl_subject_class')
            ->where('subject_class_id', $lectureClassId)
            ->value('class_name');

        // Check if the class code for the subject class exists in the database
        $classCodeExists = DB::table('tbl_class_code')
            ->where('subject_class_id_fk', $lectureClassId)
            ->exists();

        $remainingClassSize = 0;
        if ($classCodeExists){
            //Check start date and end dat of the class (Start)
            // Get the start_date and end_date for the specified subject class
            $classInfo = DB::table('tbl_class_code')
                ->select('start_date', 'end_date')
                ->where('subject_class_id_fk', $lectureClassId)
                ->first();

            // print_r($classInfo); exit;

            if ($classInfo) {
                $startDate = Carbon::parse($classInfo->start_date);
                $endDate = Carbon::parse($classInfo->end_date);
                $currentDate = Carbon::now();

                // Check if the current date is between the start and end date
                if ($currentDate->between($startDate, $endDate)) {
                    $userIdsInDatabase[] = $userId;

                    // Calculate the number of users that need to be created
                    $usersToCreateCount = 1;

                    //Get all user ID that already enrol
                    $enrolledUserIds = DB::table('tbl_subject_class_enrolment')
                        ->select('user_id_fk')
                        ->where('subject_class_id_fk', $lectureClassId)
                        ->pluck('user_id_fk')
                        ->toArray();

                    // Count number of users already enrol
                    $enrolledUserCount = count($enrolledUserIds);

                    //Get the ID of user in CSV that already exist in the databas and yet to enrol
                    $newUserIdsToEnroll = array_diff($userIdsInDatabase, $enrolledUserIds);
                    
                    // Count the number of new users to enroll
                    $newUserIdsToEnrollCount = count($newUserIdsToEnroll);

                    //Get the class size of the subject class
                    $classSize = DB::table('tbl_class_code')
                        ->where('subject_class_id_fk', $lectureClassId)
                        ->value('class_size');

                    //Check number of user in the class
                    $usersInClassCount = DB::table('tbl_subject_class_enrolment')
                        ->where('subject_class_id_fk', $lectureClassId)
                        ->count();

                    // Calculate the remaining class size
                    $remainingClassSize = $classSize - $usersInClassCount;
                }
            }

            if ($remainingClassSize > 0) {
            
                $user = DB::table('tbl_user')
                    ->where('id', $userId)
                    ->first();

                //Find all the user_id_fk in tbl_subject_class_enrolment where the subject_class_id_fk matches $lectureClassId.
                $existingUserIds = DB::table('tbl_subject_class_enrolment')
                    ->where('user_id_fk', $userId)
                    ->where('subject_class_id_fk', $lectureClassId)
                    ->get();
                
                if (count($existingUserIds) == 0) {
                    //  Insert the IDs from userIdsToEnroll that do not exist in existingUserIds into enrolment table.
                    if (Auth::check()) {
                        $currentUserId = auth()->user()->id; // Get the current login user ID
                    } else {
                        $currentUserId = $userId;
                    }
                    $createUpdated_Time = now()->toDateTimeString();
                    
                    DB::table('tbl_subject_class_enrolment')->insert([
                        'subject_class_id_fk' => $lectureClassId,
                        'user_id_fk' => $userId,
                        'updated_at' => $createUpdated_Time,
                        'created_at' => $createUpdated_Time,
                        'updated_by' => $currentUserId,
                        'created_by' => $currentUserId,
                    ]);
                }
                return true;
            }
        }

        // return false;
    }
}
