<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\frontend\ClassController;
use App\Http\Controllers\frontend\FeedbackController;
use App\Http\Controllers\frontend\SubjectController;
use App\Http\Controllers\frontend\ProfileController;
use App\Http\Controllers\BackendSystem\UserController;
use App\Http\Controllers\BackendSystem\SubjectsController;
use App\Http\Controllers\BackendSystem\SubjectEnrollmentsController;
use App\Http\Controllers\BackendSystem\ModeSiteController;
use App\Http\Controllers\BackendSystem\LectureClassesController;
use App\Http\Controllers\BackendSystem\RBAC\RBAC_AccessRightsController;
use App\Http\Controllers\BackendSystem\RBAC\RBAC_PermissionsController;
use App\Http\Controllers\BackendSystem\RBAC\RBAC_RolesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function() {

    Route::get('/login', [LoginController::class, 'show'])->name("login");

    Route::post('/user/login', [LoginController::class, 'login'])->name("user.login");

    Route::get('/classcode', [RegisterController::class, 'showClassCode'])->name("showClassCode");

    Route::post('/user/classcode', [RegisterController::class, 'checkClassCode'])->name("user.classcode");

    Route::get('/register', [RegisterController::class, 'showRegister'])->name("showRegister");

    Route::post('/user/register', [RegisterController::class, 'register'])->name("user.register");

});

Route::group(['middleware' => ['auth']], function() {

    Route::get('/', [ClassController::class, 'index'])->name("search.class");

    // Route::get('/home', [DashboardController::class, 'show']);

    Route::get('/frontend/classes', [ClassController::class, 'index'])->name("search.class");

    Route::get('/frontend/activity', [ClassController::class, 'activity'])->name("activity");

    Route::post('/user/activity', [ClassController::class, 'searchActivity'])->name("search.activity");

    Route::get('/frontend/indepth', [ClassController::class, 'indepth'])->name("indepth");

    Route::post('/user/indepth', [ClassController::class, 'searchIndepth'])->name("search.indepth");

    Route::post('/user/getClassGraphData', [ClassController::class, 'search'])->name("user.class");

    Route::get('/frontend/profile', [ProfileController::class, 'index'])->name("profile");

    Route::post('/update/profile', [ProfileController::class, 'update'])->name("profile.update");

    Route::get('/frontend/subject', [SubjectController::class, 'index'])->name("subject");

    Route::post('/user/getGraphData', [SubjectController::class, 'getGraphData'])->name("user.subject");

    Route::get('/frontend/feedback', [FeedbackController::class, 'index'])->name("feedback");

    Route::post('/user/getClassesAndTopicBySubject', [FeedbackController::class, 'getClassesAndTopicBySubject'])->name("user.getClassesAndTopicBySubject");

    Route::post('/user/getFeedbacks', [FeedbackController::class, 'getFeedbacks'])->name("user.getFeedbacks");
    

    // Route::post('/classes', [ClassController::class, 'search'])->name("search.class");

    //Backend System Routing
    //User Profile Page
    Route::get('/admin/usersProfile', [UserController::class, 'showUsersProfile']);
    // Logout
    Route::get('/auth/logout', [LoginController::class, 'logout']);


    //User Dashboard
    Route::get('/admin/usersDashboard', [UserController::class, 'showUsersDashboard']);
    //User create
    Route::post('/admin/createUser', [UserController::class, 'createUser']);
    //User Status Change
    Route::post('/admin/usersDashboardStatus', [UserController::class, 'changeUsersDashboardStatus']);
    //View User Action Button
    Route::get('/admin/userInfo/{id}', [UserController::class, 'showUserInfo']);
    //Edit User Action Button
    Route::get('/admin/userEdit/{id}', [UserController::class, 'showUserEdit']);
    //Save User Edit
    Route::post('/admin/userEditSave', [UserController::class, 'UserEditSave']);
    //Delete User Action Button
    Route::delete('/admin/deleteUser/{id}', [UserController::class, 'deleteUser']);



    //RBAC Permissions Dashboard
    Route::get('/admin/rbac_PermissionsDashboard', [RBAC_PermissionsController::class, 'showRBAC_PermissionsDashboard']);
    //Permission create
    Route::post('/admin/createPermission', [RBAC_PermissionsController::class, 'createPermission']);
    //View Permission Action Button
    Route::get('/admin/permissionInfo/{name}', [RBAC_PermissionsController::class, 'showPermissionInfo']);
    //Edit Permission Action Button
    Route::get('/admin/permissionEdit/{name}', [RBAC_PermissionsController::class, 'showPermissionEdit']);
    //Delete Action Button
    Route::delete('/admin/deletePermission/{name}', [RBAC_PermissionsController::class, 'deletePermission']);


    //RBAC Roles Dashboard
    Route::get('/admin/rbac_RolesDashboard', [RBAC_RolesController::class, 'showRBAC_RolesDashboard']);
    //Role create
    Route::post('/admin/createRole', [RBAC_RolesController::class, 'createRole']);
    //View Permission Action Button
    Route::get('/admin/roleInfo/{name}', [RBAC_RolesController::class, 'showRoleInfo']);
    //Edit Permission Action Button
    Route::get('/admin/roleEdit/{name}', [RBAC_RolesController::class, 'showRoleEdit']);
    //Delete Role
    Route::delete('/admin/deleteRole/{name}', [RBAC_RolesController::class, 'deleteRole']);


    //RBAC Access Right Dashboard
    Route::get('/admin/rbac_AccessRightsDashboard', [RBAC_AccessRightsController::class, 'showRBAC_AccessRightsDashboard']);
    //View Assign Action Button
    Route::get('/admin/assignRightInfo/{id}', [RBAC_AccessRightsController::class, 'showAccessRightInfo']);
    //Edit Assign Action Button
    Route::get('/admin/assignRightEdit/{id}', [RBAC_AccessRightsController::class, 'showaccessRightEdit']);
    //Delete User Action Button
    Route::delete('/admin/deleteAssignRight/{id}', [RBAC_AccessRightsController::class, 'deleteAssignRight']);
    //Save AccessRight Edit
    Route::post('/admin/accessRightEditSave', [RBAC_AccessRightsController::class, 'accessRightEditSave']);


    //Subjects Dashboard
    Route::get('/admin/subjectsDashboard', [SubjectsController::class, 'showSubjectsDashboard']);

    Route::post('/admin/create/subject', [SubjectsController::class, 'createSubject']);

    Route::post('/admin/update/subject', [SubjectsController::class, 'updateSubject']);

    Route::post('/admin/delete/subject', [SubjectsController::class, 'deleteSubject']);

    Route::post('/admin/subject/status', [SubjectsController::class, 'changeSubjectStatus']);

    //View subject Action Button
    Route::get('/admin/subjectInfo/{id}', [SubjectsController::class, 'showSubjectInfo']);
    
    
    //Topics Dashboard
    Route::get('/admin/topicsDashboard/{id}', [SubjectsController::class, 'showTopicsDashboard']);


    //Subject Enrollments Dashboard
    Route::get('/admin/subjectEnrollmentsDashboard', [SubjectEnrollmentsController::class, 'showSubjectEnrollmentsDashboard']);


    //Lecture Classes Dashboard
    Route::get('/admin/lectureClassesDashboard', [LectureClassesController::class, 'showLectureClassesDashboard']);
    //lecture Class create
    Route::post('/admin/createLectureClass', [LectureClassesController::class, 'createLectureClass']);
    //View Lecture Class Action Button
    Route::get('/admin/lectureClassInfo/{id}', [LectureClassesController::class, 'showLectureClassInfo']);
    //Delete function
    Route::delete('/admin/deleteLectureClass/{id}', [LectureClassesController::class, 'deleteLectureClass']);
    //manage student function
    Route::get('/admin/manageStudentDashboard/{id}', [LectureClassesController::class, 'showManageStudentDashboard']);
    //Enrol student function
    Route::get('/admin/enrolStudentDashboard', [LectureClassesController::class, 'showEnrolStudentDashboard']);
    //Download Enrol Student Template file
    Route::get('/admin/enrolStudentTemplate', [LectureClassesController::class, 'downloadEnrolStudentTemplate']);
    //Upload Enrol Student file
    Route::post('/admin/uploadEnrolStudentFile', [LectureClassesController::class, 'uploadEnrolStudentFile']);



    //Mode Site Dashboard
    Route::get('/admin/modeSiteDashboard', [ModeSiteController::class, 'showModeSiteDashboard']);

});
