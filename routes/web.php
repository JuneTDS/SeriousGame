<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\auth\LoginController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/login', [LoginController::class, 'show']);

Route::get('/dashboard', [DashboardController::class, 'show']);

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