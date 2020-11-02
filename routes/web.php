<?php

use App\TaskManagement\TasksController;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Route;
use Imanghafoori\HeyMan\Facades\HeyMan;
use Imanghafoori\HeyMan\StartGuarding;

/*for($i=0; $i< 100; $i++) {
    Event::listen('asdcasdc', 'class@method');
}*/
Route::get('/qqq', function () {
    HeyMan::here(function($q) {
        $q->thisClosureShouldAllow(function (){
            return false;
        })
        ->otherwise()
        ->redirect()->to('/aaaaa');
    });
    resolve(StartGuarding::class)->start();

    return ['asdcasd'];
});
Route::view('/', 'welcome')->name('welcome');

Route::view('tasks', 'tasks.index')->name('tasks.index');
Route::view('tasks/create', 'tasks.create')->name('tasks.create');

Route::post('tasks', [TasksController::class, 'store'])
    ->middleware('redirect:tasks.index')
    ->name('tasks.store');

Route::get('tasks/{task}/edit', [TasksController::class, 'edit'])
    ->middleware('view:tasks.edit')
    ->name('tasks.edit');

Route::put('tasks/{task}', [TasksController::class, 'update'])
    ->middleware('redirect:tasks.index')
    ->name('tasks.update');

Route::delete('tasks/{task}', [TasksController::class, 'destroy'])
    ->middleware('redirect:tasks.index')
    ->name('tasks.destroy');


###########        Authentication routes:        ###########
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register')->name('register.post');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');


