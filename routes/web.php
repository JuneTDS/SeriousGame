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

    Route::get('/', [DashboardController::class, 'show']);

    Route::get('/home', [DashboardController::class, 'show']);

    Route::get('/frontend/classes', [ClassController::class, 'index'])->name("search.class");

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
    Route::post('/admin/usersDashboardStatus/{id}', [UserController::class, 'changeUsersDashboardStatus']);
    //View User Action Button
    Route::get('/admin/userInfo/{id}', [UserController::class, 'showUserInfo']);
    //Edit User Action Button
    Route::get('/admin/userEdit/{id}', [UserController::class, 'showUserEdit']);
    //Delete User Action Button
    Route::delete('/admin/deleteUser/{id}', [UserController::class, 'deleteUser']);



    //RBAC Permissions Dashboard
    Route::get('/admin/rbac_PermissionsDashboard', [RBAC_PermissionsController::class, 'showRBAC_PermissionsDashboard']);


    //RBAC Roles Dashboard
    Route::get('/admin/rbac_RolesDashboard', [RBAC_RolesController::class, 'showRBAC_RolesDashboard']);


    //RBAC Access Right Dashboard
    Route::get('/admin/rbac_AccessRightsDashboard', [RBAC_AccessRightsController::class, 'showRBAC_AccessRightsDashboard']);


    //Subjects Dashboard
    Route::get('/admin/subjectsDashboard', [SubjectsController::class, 'showSubjectsDashboard']);


    //Subject Enrollments Dashboard
    Route::get('/admin/subjectEnrollmentsDashboard', [SubjectEnrollmentsController::class, 'showSubjectEnrollmentsDashboard']);


    //Lecture Classes Dashboard
    Route::get('/admin/lectureClassesDashboard', [LectureClassesController::class, 'showLectureClassesDashboard']);


    //Mode Site Dashboard
    Route::get('/admin/modeSiteDashboard', [ModeSiteController::class, 'showModeSiteDashboard']);

});
