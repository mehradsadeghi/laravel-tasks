<?php

// public homepage route
Route::view('/', 'welcome');

// instantiate auth routing and establish logout route
Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

// user homepage route
Route::get('/home', 'HomeController@index');

// user tasks routes
Route::resource('/tasks', 'TasksController');
