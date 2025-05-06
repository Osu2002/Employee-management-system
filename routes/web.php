<?php

use App\Http\Controllers\BackendAuthController;
use App\Http\Controllers\BackendUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FrontendUserController;
use App\Http\Controllers\MediaLibraryController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\JobRoleController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ContactHRController;

use App\Http\Controllers\EmployeeFrontendController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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


Route::group(['middleware' => ['auth:sanctum', 'verified', 'web']], function () {
    Route::get('/two-step-verification', [BackendAuthController::class, 'twoStepRequest'])->name('2fa.request');
    Route::post('/two-step-verification', [BackendAuthController::class, 'twoStepVerify'])->name('2fa.verify');
    Route::get('/activate', [BackendAuthController::class, 'activateProfileView'])->name('profile.activate.request');
    Route::post('/activate', [BackendAuthController::class, 'activateProfile'])->name('profile.activate');
});

Route::group(['middleware' => ['auth:sanctum', 'web']], function () {
    Route::post('/logout', function () {
        Auth::guard('web')->logout();
        return redirect()->route('dashboard');
    })->name('logout');
});


Route::group(['middleware' => ['auth:sanctum', 'verified', 'web', 'profile.activate']], function () {

    Route::get('/', function () {
        return redirect('/admin/dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');







    // Route::middleware(['employee.auth'])->group(function () {
    //     Route::get('/home', [EmployeeFrontendController::class, 'home'])->name('home');
    //     Route::get('/profiledetails/{emp_id}', [EmployeeFrontendController::class, 'profiledetails'])->name('profiledetails');
    // });


    Route::post('/employee/leaves/update-status', [LeaveController::class, 'updateStatus'])->name('employee.leaves.update_status');

    // Employee
    Route::prefix('employee')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->middleware(['can:employee.view'])->name('employee');
        Route::get('/create', [EmployeeController::class, 'create'])->middleware(['can:employee.create'])->name('employee.create');
        Route::post('/store', [EmployeeController::class, 'store'])->middleware(['can:employee.create'])->name('employee.store');
        Route::get('/getdata', [EmployeeController::class, 'getdata'])->middleware(['can:employee.view'])->name('employee.getdata');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->middleware(['can:employee.edit'])->name('employee.edit');
        Route::post('/update', [EmployeeController::class, 'update'])->middleware(['can:employee.edit'])->name('employee.update');
        Route::post('/destroy', [EmployeeController::class, 'destroy'])->middleware(['can:employee.delete'])->name('employee.destroy');
        Route::put('/update/status', [EmployeeController::class, 'updateStatus'])->middleware(['can:employee.edit'])->name('employee.change.status');

        Route::get('/{id}/leaves', [EmployeeController::class, 'Leaves'])->middleware(['can:employee.leaves'])->name('employee.leaves.index');
        Route::get('/leavegetdata/{id}', [EmployeeController::class, 'leavegetdata'])->middleware(['can:employee.leaves'])->name('employee.leaves.getdata');
        Route::get('/leaves_create/{id}', [EmployeeController::class, 'leavescreate'])->middleware(['can:employee.leaves'])->name('employee.leaves.create');
        Route::post('/leaves_store', [EmployeeController::class, 'leaves_store'])->middleware(['can:employee.create'])->name('employee.leaves_store');
        Route::post('/leaves_update', [EmployeeController::class, 'leaves_update'])->middleware(['can:employee.create'])->name('employee.leaves.update');
        Route::get('/{empid}/leaves_edit/{id}', [EmployeeController::class, 'leaves_edit'])->middleware(['can:employee.create'])->name('employee.leaves.edit');
        Route::post('/leave_delete', [EmployeeController::class, 'leaves_distroy'])->name('employee.leaves.destroy');

        Route::get('/document/{id}', [EmployeeController::class, 'document'])->middleware(['can:employee.document'])->name('employee.document');
        Route::post('/document_store', [EmployeeController::class, 'document_store'])->middleware(['can:employee.document'])->name('employee.document.store');
        Route::delete('/employee/{employee_id}/document/{id}', [EmployeeController::class, 'document_distroy'])->name('employee.document.destroy');
        Route::put('/employee/document/{id}/update-display-status', [EmployeeController::class, 'updateDisplayStatus'])->name('employee.document.update_display_status');
        //Service Letter Display Rout

        Route::get('/certificate/{id}', [EmployeeController::class, 'certificate'])->middleware(['can:employee.view'])->name('employee.certificate');

        Route::get('/bank/{id}', [EmployeeController::class, 'bank_details'])->middleware(['can:employee.edit'])->name('employee.bank_details');
        Route::post('/bank_details_store/store', [EmployeeController::class, 'bank_details_store'])->middleware(['can:employee.edit'])->name('employee.bank.store');
        Route::get('/bank_details_edit/{id}', [EmployeeController::class, 'bank_details_edit'])->middleware(['can:employee.edit'])->name('employee.bank_details_edit');
        Route::post('/bank/update', [EmployeeController::class, 'bank_details_update'])->middleware(['can:employee.edit'])->name('employee.bank.update');

        Route::get('/service_letter/{id}', [EmployeeController::class, 'service_letter'])->middleware(['can:employee.service_letter'])->name('employee.service_letter');
        Route::post('/ServiceLetter_store', [EmployeeController::class, 'ServiceLetter_store'])->middleware(['can:employee.service_letter'])->name('employee.ServiceLetter.store');
        Route::post('/ServiceLetter_update', [EmployeeController::class, 'ServiceLetter_update'])->middleware(['can:employee.service_letter'])->name('employee.ServiceLetter.update');
        Route::get('/ServiceLetter_view/{id}', [EmployeeController::class, 'ServiceLetter_view'])->middleware(['can:employee.service_letter'])->name('employee.ServiceLetter.view');


        Route::middleware(['auth'])->group(function () {
            Route::get('/admin/projects/{employeeId}', [EmployeeController::class, 'adminProjects'])
                ->name('admin.projects');
        });
        Route::post('/services/toggle-visibility', [EmployeeController::class, 'toggleServiceLetter'])->name('services.toggle-visibility');
        Route::get('/leaves', [LeaveController::class, 'adminIndex'])->middleware(['can:leaves.view'])->name('admin.leaves.index');
        Route::get('/leaves/{id}/edit', [LeaveController::class, 'edit'])->middleware(['can:leaves.edit'])->name('admin.leaves.edit');
        Route::post('/leaves/{id}/update', [LeaveController::class, 'update'])->middleware(['can:leaves.edit'])->name('admin.leaves.update');
        Route::delete('/leaves/{id}', [LeaveController::class, 'destroy'])->middleware(['can:leaves.delete'])->name('admin.leaves.destroy');


    });


    // profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [BackendAuthController::class, 'showProfile'])->name('profile');
        Route::put('/update-user-photo', [BackendAuthController::class, 'uploadProfilePhoto'])->middleware(['can:profile.updatePhoto'])->name('profile.update-photo');
        Route::put('/update-user-info', [BackendAuthController::class, 'updateUserInfo'])->middleware(['can:profile.updateInfo'])->name('profile.update-info');
        Route::put('/change-password', [BackendAuthController::class, 'changePassword'])->middleware(['can:profile.updatePassword'])->name('profile.update-password');
        Route::post('/disable', [BackendAuthController::class, 'disableProfile'])->middleware(['can:profile.deactivate'])->name('profile.disable');
        Route::post('/delete', [BackendAuthController::class, 'deleteProfile'])->middleware(['can:profile.delete'])->name('profile.delete');
    });
    // settings
    Route::prefix('settings')->group(function () {
        Route::get('/general', [SettingsController::class, 'generalSettings'])->middleware(['can:settings.view'])->name('settings.general');
        Route::post('/general', [SettingsController::class, 'generalSettingsUpdate'])->middleware(['can:settings.edit'])->name('settings.general.update');
        Route::get('/mail', [SettingsController::class, 'mailSettings'])->middleware(['can:settings.view'])->name('settings.mail');
        Route::post('/mail', [SettingsController::class, 'mailSettingsUpdate'])->middleware(['can:settings.edit'])->name('settings.mail.update');
        Route::get('/social-auth', [SettingsController::class, 'socialAuthSettings'])->middleware(['can:settings.view'])->name('settings.social-auth');
        Route::post('/social-auth', [SettingsController::class, 'socialAuthSettingsUpdate'])->middleware(['can:settings.edit'])->name('settings.social-auth.update');
        Route::get('/content-pages/{id}', [SettingsController::class, 'socialAuthSettings'])->middleware(['can:settings.view'])->name('settings.content-pages.edit');
        Route::post('/content-pages', [SettingsController::class, 'socialAuthSettingsUpdate'])->middleware(['can:settings.edit'])->name('settings.content-pages.update');
    });
    // media library
    Route::prefix('media-library')->group(function () {
        Route::get('/', [MediaLibraryController::class, 'index'])->middleware(['can:media.view'])->name('media.index');
        Route::post('/upload', [MediaLibraryController::class, 'upload'])->middleware(['can:media.create'])->name('media.upload');
        Route::get('/download/{id}', [MediaLibraryController::class, 'download'])->middleware(['can:media.edit'])->name('media.download');
        Route::post('/delete', [MediaLibraryController::class, 'delete'])->middleware(['can:media.delete'])->name('media.delete');
    });
    // rolses & permissions
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->middleware(['can:roles-permissions.view'])->name('settings.roles');
        Route::get('/getdata', [RoleController::class, 'getdata'])->middleware(['can:roles-permissions.view'])->name('settings.roles.getdata');
        Route::get('/create', [RoleController::class, 'create'])->middleware(['can:roles-permissions.create'])->name('settings.roles.create');
        Route::post('/store', [RoleController::class, 'store'])->middleware(['can:roles-permissions.create'])->name('settings.roles.store');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->middleware(['can:roles-permissions.edit'])->name('settings.roles.edit');
        Route::post('/update', [RoleController::class, 'update'])->middleware(['can:roles-permissions.edit'])->name('settings.roles.update');
        Route::get('/permissions/{id}', [RoleController::class, 'editPermissions'])->middleware(['can:roles-permissions.edit'])->name('settings.roles.permissions');
        Route::post('/permissions/update', [RoleController::class, 'updatePermissions'])->middleware(['can:roles-permissions.edit'])->name('settings.roles.permissions.update');
        Route::post('/delete', [RoleController::class, 'destroy'])->middleware(['can:roles-permissions.delete'])->name('settings.roles.delete');
    });
    // backend users
    Route::prefix('backend-users')->group(function () {
        Route::get('/', [BackendUserController::class, 'index'])->middleware(['can:backend-user.view'])->name('settings.users');
        Route::get('/get/data', [BackendUserController::class, 'getData'])->middleware(['can:backend-user.view'])->name('settings.users.getdata');
        Route::get('/create', [BackendUserController::class, 'create'])->middleware(['can:backend-user.create'])->name('settings.users.create');
        Route::post('/store', [BackendUserController::class, 'store'])->middleware(['can:backend-user.create'])->name('settings.users.store');
        Route::get('/edit/{id}', [BackendUserController::class, 'edit'])->middleware(['can:backend-user.edit'])->name('settings.users.edit');
        Route::post('/update', [BackendUserController::class, 'update'])->middleware(['can:backend-user.edit'])->name('settings.users.update');
        Route::put('/update/status', [BackendUserController::class, 'updateStatus'])->middleware(['can:backend-user.edit'])->name('settings.users.change.status');
        Route::put('/update/password', [BackendUserController::class, 'updatePassword'])->middleware(['can:backend-user.edit'])->name('settings.users.change.password');
        Route::post('/suspend', [BackendUserController::class, 'softDelete'])->middleware(['can:backend-user.delete'])->name('settings.users.delete');
        Route::post('/restore', [BackendUserController::class, 'restore'])->middleware(['can:backend-user.delete'])->name('settings.users.restore');
        Route::post('/delete', [BackendUserController::class, 'delete'])->middleware(['can:backend-user.delete'])->name('settings.users.remove');
        //Route::get('/employee/{id}/service-letter', [EmployeeController::class, 'ServiceLetter_view'])->middleware(['can:backend-user..view'])->name('employee.service_letter_view');
        //Route::post('/service-letter/toggle-visibility/{id}', [BackendUserController::class, 'toggleServiceLetter'])->middleware(['can:backend-user.view'])->name('service-letter.toggle-visibility');
        Route::post('/services/{id}/toggle-visibility', [BackendUserController::class, 'toggleServiceLetter'])
    ->name('services.toggle-visibility');


    });
    // frontend users
    Route::prefix('front-users')->group(function () {
        Route::get('/', [FrontendUserController::class, 'index'])->middleware(['can:frontend-user.view'])->name('settings.front-user');
        Route::get('/get/data', [FrontendUserController::class, 'getData'])->middleware(['can:frontend-user.view'])->name('settings.front-user.getdata');
        Route::get('/create', [FrontendUserController::class, 'create'])->middleware(['can:frontend-user.create'])->name('settings.front-user.create');
        Route::post('/store', [FrontendUserController::class, 'store'])->middleware(['can:frontend-user.create'])->name('settings.front-user.store');
        Route::get('/edit/{id}', [FrontendUserController::class, 'edit'])->middleware(['can:frontend-user.edit'])->name('settings.front-user.edit');
        Route::post('/update', [FrontendUserController::class, 'update'])->middleware(['can:frontend-user.edit'])->name('settings.front-user.update');
        Route::put('/update/status', [FrontendUserController::class, 'updateStatus'])->middleware(['can:frontend-user.edit'])->name('settings.front-user.change.status');
        Route::put('/update/password', [FrontendUserController::class, 'updatePassword'])->middleware(['can:frontend-user.edit'])->name('settings.front-user.change.password');
        Route::post('/delete', [FrontendUserController::class, 'destroy'])->middleware(['can:frontend-user.delete'])->name('settings.front-user.delete');
    });

    // Grouping routes under 'admin' prefix
    Route::prefix('job-roles')->group(function () {
        Route::get('/', [JobRoleController::class, 'index'])->middleware(['can:job-roles.view'])->name('job-roles.index');
        Route::get('/get/data', [JobRoleController::class, 'getJobRolesData'])->middleware(['can:job-roles.view'])->name('job-roles.getdata');
        Route::get('/create', [JobRoleController::class, 'create'])->middleware(['can:job-roles.create'])->name('job-roles.create');
        Route::post('/store', [JobRoleController::class, 'store'])->middleware(['can:job-roles.create'])->name('job-roles.store');
        Route::get('/edit/{jobRole}', [JobRoleController::class, 'edit'])->middleware(['can:job-roles.edit'])->name('job-roles.edit');
        Route::POST('/update', [JobRoleController::class, 'update'])->middleware(['can:job-roles.edit'])->name('job-roles.update');
        Route::put('/update/status', [JobRoleController::class, 'updateStatus'])->middleware(['can:job-roles.edit'])->name('job-roles.change.status');
        Route::post('/delete', [JobRoleController::class, 'destroy'])->middleware(['can:job-roles.delete'])->name('job-roles.destroy');
    });

    Route::get('/employee/{id}/leave-types', [LeaveController::class, 'getLeaveTypes']);
    Route::post('/employee/leaves/{id}', [LeaveController::class, 'destroy'])->name('employee.leaves.destroy');

    // routes/web.php
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        Route::get('/contact-hr', [ContactHRController::class, 'adminIndex'])->name('contact.hr.index');
        Route::get('/contact-hr/chat-history/{id}', [ContactHRController::class, 'getChatHistory']);


        Route::delete('/contact-hr/{id}', [ContactHRController::class, 'destroy'])->name('contact.hr.destroy');
        Route::get('/contact-hr/data', [ContactHRController::class, 'getContactHRMessagesData'])->name('contact.hr.getdata');
    });

    Route::post('/contact-hr/reply/{id}', [ContactHRController::class, 'reply'])->name('contact.hr.reply');
});
