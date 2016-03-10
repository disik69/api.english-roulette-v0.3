<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/create-collocations', 'SandboxController@createCollocations');
Route::get('/create-exercise', 'SandboxController@createExercise');
Route::get('/user/{id}/exercises', 'SandboxController@getUserExercises');

Route::get('/check-captcha', 'SandboxController@checkCaptcha');

Route::get('/test-user', [
    'middleware' => ['jwt.auth', 'acl'],
    'is' => 'user', 'uses' => 'SandboxController@testUser'
]);
Route::get('/test-admin', [
    'middleware' => ['jwt.auth', 'acl'],
    'is' => 'admin', 'uses' => 'SandboxController@testAdmin'
]);

Route::post('signup', 'SignController@up');
Route::post('signin', 'SignController@in');
Route::get('check-email', 'SignController@checkEmail');
Route::get('debug-token',['middleware' => 'jwt.auth', 'uses' => 'SignController@debug']);
