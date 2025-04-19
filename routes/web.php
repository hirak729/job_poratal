<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;


Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/jobs',[JobsController::class,'index'])->name('jobs');
Route::get('/jobs/details/{id}',[JobsController::class,'details'])->name('jobDetails');
Route::post('/apply-job',[JobsController::class,'applyJob'])->name('applyJob');
Route::post('/save-job',[JobsController::class,'saveJob'])->name('saveJob');


Route::group(['prefix' => 'admin', 'middleware' => 'checkRole'], function(){
    Route::get('dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
    Route::get('users',[UserController::class,'index'])->name('admin.users');
    Route::get('users/{id}',[UserController::class,'edit'])->name('admin.users.edit');
    Route::post('users/{id}',[UserController::class,'update'])->name('admin.users.update');
    Route::post('users/{id}',[UserController::class,'update'])->name('admin.users.update');
    Route::delete('users',[UserController::class,'destroy'])->name('admin.users.destroy');
    Route::get('jobs',[JobController::class,'index'])->name('admin.jobs');
    Route::get('jobs/{id}',[JobController::class,'edit'])->name('admin.jobs.edit');
    Route::post('jobs/{id}',[JobController::class,'update'])->name('admin.jobs.update');
    Route::delete('jobs',[JobController::class,'destroy'])->name('admin.jobs.destroy');

});


Route::group(['prefix' => 'account'], function(){

    //guest route

    Route::group(['middleware'=>'guest'], function(){
        Route::get('register',[AccountController::class,'registration'])->name('account.registration');
        Route::post('process-register',[AccountController::class,'processRegistration'])->name('account.processRegistration');
        Route::get('login',[AccountController::class,'login'])->name('account.login');
        Route::post('authenticate',[AccountController::class,'authenticate'])->name('account.authenticate');
    });

    //authenticated route

    Route::group(['middleware'=>'auth'], function(){
        Route::get('profile',[AccountController::class,'profile'])->name('account.profile');
        Route::get('logout',[AccountController::class,'logout'])->name('account.logout');
        Route::post('update-profile',[AccountController::class,'updateProfile'])->name('account.updateProfile');
        Route::post('update-profile-pic',[AccountController::class,'updateProfilePic'])->name('account.updateProfilePic');
        Route::post('update-password',[AccountController::class,'updatePassword'])->name('account.updatePassword');
        Route::get('create-job',[AccountController::class,'createJob'])->name('account.createJob');
        Route::post('save-job',[AccountController::class,'saveJob'])->name('account.saveJob');
        Route::get('my-jobs',[AccountController::class,'myJobs'])->name('account.myJobs');
        Route::get('my-jobs/edit/{jobId}',[AccountController::class,'editJob'])->name('account.editJob');
        Route::post('update-job/{jobId}',[AccountController::class,'updateJob'])->name('account.updateJob');
        Route::post('delete-job',[AccountController::class,'deleteJob'])->name('account.deleteJob');
        Route::get('my-job-applications',[AccountController::class,'myJobApplications'])->name('account.myJobApplications');
        Route::post('remove-job-application',[AccountController::class,'removeJobs'])->name('account.removeJobs');
        Route::get('saved-jobs',[AccountController::class,'savedJobs'])->name('account.savedJobs');
        Route::post('remove-saved-jod',[AccountController::class,'removesavedJobs'])->name('account.removesavedJobs');
        
    });

    
});



