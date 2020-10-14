<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

Route::view('/', 'welcome');
Route::view('/home', 'home')->middleware('auth');


Route::get('tasks', [TasksController::class, 'index'])->name('tasks.index');
Route::view('tasks/create', 'tasks.create')->name('tasks.create');
Route::post('tasks', [TasksController::class, 'store'])->name('tasks.store');


Route::get('tasks/{task}/edit', [TasksController::class, 'edit'])
    ->middleware('view:tasks.edit')
    ->name('tasks.edit');

Route::put('tasks/{task}', [TasksController::class, 'update'])->name('tasks.update');
Route::delete('tasks/{task}', [TasksController::class, 'destroy'])->name('tasks.destroy');


###########        Authentication routes:        ###########
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');


