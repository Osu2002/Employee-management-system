<?php

use App\Http\Controllers\EmployeeFrontendController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\BackendAuthController;
use App\Http\Controllers\EmployeeController;

use App\Http\Controllers\ContactHRController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
| Here is where you can register frontend routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "frontend" middleware group. Now create something great!
*/

Route::get('/', function () {
    return redirect('/landing-page');
});

Route::get('/landing-page', [EmployeeFrontendController::class, 'index'])->name('index');
Route::get('/contact', [EmployeeFrontendController::class, 'contact'])->name('contact');
Route::get('/about', [EmployeeFrontendController::class, 'about'])->name('about');


Route::prefix('employee')->middleware(['auth:employee'])->group(function () {
    Route::put('leaves/{id}/update', [LeaveController::class, 'updateLeave'])->name('employee.leaves.update');
    Route::delete('leaves/{id}/cancel', [LeaveController::class, 'cancel'])->name('employee.leaves.cancel');
});
// Route::get('/employee/{empid}/leaves/{id}/edit', [LeaveController::class, 'editLeave'])->name('employee.leaves.edit');

// Employee frontend routes
Route::get('/home', [EmployeeFrontendController::class, 'home'])->name('home-emp');
Route::get('/employee/qr/{slug}/{id}', [EmployeeFrontendController::class, 'profiledetails'])->name('qr');
Route::get('/otp/{id}', [EmployeeFrontendController::class, 'otp'])->name('otp');
Route::get('/checkotp', [EmployeeFrontendController::class, 'checkotp'])->name('checkotp');
Route::get('/validateotp', [EmployeeFrontendController::class, 'validateotp'])->name('validateotp');



Route::group(['middleware' => ['auth:sanctum', 'verified', 'web']], function () {
    Route::get('/two-step-verification', [BackendAuthController::class, 'twoStepRequest'])->name('2fa.request');
    Route::post('/two-step-verification', [BackendAuthController::class, 'twoStepVerify'])->name('2fa.verify');

    Route::get('/activate', [BackendAuthController::class, 'activateProfileView'])->name('profile.activate.request');
    Route::post('/activate', [BackendAuthController::class, 'activateProfile'])->name('profile.activate');
});
// Employee Leave Routes
Route::get('/frontlogin', [EmployeeFrontendController::class, 'frontlogin'])->name('frontlogin');
Route::post('/emplogin', [EmployeeFrontendController::class, 'processLogin'])->name('employee.login'); // Process login





Route::group(['middleware' => ['auth:employee']], function () {
    Route::get('/profiledetails', [EmployeeFrontendController::class, 'profiledetails'])->name('profiledetails');
    Route::post('/employeelogout', [EmployeeFrontendController::class, 'employeelogout'])->name('employee.logout'); // Logout
    Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/employee/leaves', [LeaveController::class, 'index'])->name('employee.leaves.indexform');
    Route::get('/contact-hr', [ContactHRController::class, 'index'])->name('contact-hr.index');
    Route::post('/contact-hr/store', [ContactHRController::class, 'store'])->name('contact-hr.store');
    Route::get('/contact-hr/messages', [ContactHRController::class, 'getMessages'])->name('contact.hr');
    Route::get('/contact-hr/chat-history/{id}', [ContactHRController::class, 'getChatHistory']);
    
    Route::post('/contact-hr/employee-reply/{id}', [ContactHRController::class, 'employeeReply'])->name('emplyee.reply');
    // Route::get('/contact-hr/chat-history/{id}', [ContactHRController::class, 'getChatHistory']);
    Route::delete('/contact-hr/{id}', [ContactHRController::class, 'destroy'])->name('contact.hr.destroy');
    
    Route::post('/upload-cover-photo', [UserController::class, 'uploadCoverPhoto'])->name('upload.cover');
    Route::post('/user-data', [UserController::class, 'store'])->name('user.data');
    Route::get('/projects/{employeeId}', [UserController::class, 'getProjects']);
    Route::put('/projects/{project_id}', [UserController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [UserController::class, 'deleteProject']);
});



Route::patch('/admin/projects/{id}/approve', [EmployeeController::class, 'approveProject'])
    ->name('admin.projects.approve');
// Route::post('/backend/get-service-letter-visibility', [EmployeeController::class, 'toggleVisibility']);
