<?php

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

Auth::routes();

Route::group(['middleware' => ['auth']], function()
{
	Route::get('/', function () {
	    return view('welcome');
	});

	Route::get('home', 'HomeController@index')->name('home');

	Route::resource('user', 'UserController');
	Route::get('students/{id}', 'UserController@students');
	Route::resource('permission', 'PermissionController');
	Route::resource('permission_group', 'PermissionGroupController');
	Route::resource('userRole', 'UserRoleController');

	// Route::post('userCreate', 'UserController@userCreate');

	Route::get('roles', 'UserController@getRoles');
	Route::post('export_csv', 'UserController@exportCsv');
});

// Route::post('user/create', 'UserController@');
