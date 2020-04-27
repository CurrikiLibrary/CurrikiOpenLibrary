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

Route::get('/', 'HomeController@index');
Route::get('/dashboard', 'HomeController@dashboard2');
Route::get('/lesson/{id}', 'LessonController@index');
Route::get('/lessons/{id}', 'LessonController@tempindex');

Route::get('/lesson/{lesson_id}/activity/{activity_id}', 'ActivityController@index');
Route::get('/activities/{activity_id}', 'ActivityController@index2');

Route::get('/admin/lessons', 'LessonAdminController@index');
Route::get('/admin/lessons/create', 'LessonAdminController@create');
Route::get('/admin/lessons/edit/{id}', 'LessonAdminController@edit');
Route::get('/admin/lessons/delete/{id}', 'LessonAdminController@delete');
Route::post('/admin/lessons/save', 'LessonAdminController@save');	

Route::get('/admin/lessons/view/{id}', 'ActivityAdminController@index');
Route::get('/admin/activities/create', 'ActivityAdminController@create');
Route::get('/admin/activities/edit/{id}', 'ActivityAdminController@edit');
Route::get('/admin/activities/delete/{id}', 'ActivityAdminController@delete');
Route::post('/admin/activities/save', 'ActivityAdminController@save');	




Route::get('/lti-launch', 'LtiController@launch');
//Route::get('/lti-manage', 'LtiController@manage');
Route::any('/lti-manage', 'LtiController@manage');
Route::any('/lti/1p1/service', 'LtiController@lti1p1Service');
Route::any('/lti/test', 'LtiController@test');





Route::prefix('oauth1')->group(function () {
    Route::get('/', 'Oauth1Controller@index');	
    Route::get('/connect', 'Oauth1Controller@connect');
    Route::get('/page', 'Oauth1Controller@page');
    Route::get('/save', 'Oauth1Controller@save');
});