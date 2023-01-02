<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\PasswordResetController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollsController;
use App\Http\Controllers\Location\CountryController;
use App\Http\Controllers\Location\DistrictController;
use App\Http\Controllers\Location\DivisionController;
use App\Http\Controllers\modulesController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// @ pUBLIC route
Route::post('/registration', [AuthController::class, 'userRegistration']);
Route::post('/login', [AuthController::class, 'userLogin']);
Route::post('/send-reset-password-email', [PasswordResetController::class, 'sendResetPasswordEmail']);
Route::post('/reset/{token}', [PasswordResetController::class, 'resetPassword']);



Route::middleware(['auth:sanctum'])->group(function () {
    // logout
    Route::post('/logout', [AuthController::class, 'userLogout']);
    Route::get('/me', [AuthController::class, 'LoggedUser']);
    Route::get('/changepassword', [AuthController::class, 'changePassword']);

    // Students
    Route::get('students', [StudentsController::class, 'getAllStudent']);


    // Courses
    Route::post('course/new', [CourseController::class, 'addNewCourse']);
    Route::put('course/{id}', [CourseController::class, 'updateCourse']);
    Route::delete('course/delete/{id}', [CourseController::class, 'deleteCourse']);

    // students
    Route::put('student/{id}', [StudentsController::class, 'updateStudents']);
    Route::delete('student/delete/{id}', [StudentsController::class, 'deleteStudents']);
});

// @ Location

Route::get('countries', [CountryController::class, 'getAllCountry']);
Route::get('divisons/{id}', [DivisionController::class, 'getDivision']);
Route::get('districts/{id}', [DistrictController::class, 'getDistrict']);

// @ USERS
Route::get('users', [userController::class, 'getAllUser']);
Route::get('user/{id}', [userController::class, 'getSingleUser']);
Route::put('user/{id}', [userController::class, 'updateUser']);
Route::delete('user/delete/{id}', [userController::class, 'deleteUser']);

// @ courses
Route::get('courses', [CourseController::class, 'getAllCourse']);
Route::get('course/{id}', [CourseController::class, 'getSingleCourse']);


// @ modules
Route::get('modules', [modulesController::class, 'getAllModule']);
Route::get('module/{id}', [modulesController::class, 'getSingleModule']);
Route::post('module/new', [modulesController::class, 'addNewModule']);
Route::put('module/{id}', [modulesController::class, 'updateModule']);
Route::delete('module/delete/{id}', [modulesController::class, 'deleteModule']);


// @ students

Route::get('student/{id}', [StudentsController::class, 'getSingleStudents']);
Route::post('student', [StudentsController::class, 'addNewstudent']);


// @ enrolls
Route::get('enrolls', [EnrollsController::class, 'getAllEnroll']);
Route::get('enroll/{id}', [EnrollsController::class, 'getSingleEnroll']);
Route::post('enroll/new', [EnrollsController::class, 'addNewEnroll']);
Route::put('enroll/{id}', [EnrollsController::class, 'updateEnroll']);
Route::delete('enroll/delete/{id}', [EnrollsController::class, 'deleteEnroll']);
