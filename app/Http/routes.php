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

/*
 * test section
 */
//Route::get('create-collocations', 'SandboxController@createCollocations');
//Route::get('create-exercise', 'SandboxController@createExercise');
//Route::get('user/{id}/exercises', 'SandboxController@getUserExercises');
//
//Route::get('yandex-dictionary', 'SandboxController@yandexDictionary');
//
//Route::get('check-captcha', 'SandboxController@checkCaptcha');
//
//Route::get('get-exercise/{exercise}', 'SandboxController@getExercise');
//
//Route::get('jwt', 'SandboxController@jwt');
//
//Route::get('test-user', [
//    'middleware' => ['jwt.auth', 'acl'],
//    'is' => 'user', 'uses' => 'SandboxController@testUser'
//]);
//Route::get('test-admin', [
//    'middleware' => ['jwt.auth', 'acl'],
//    'is' => 'admin', 'uses' => 'SandboxController@testAdmin'
//]);
//Route::get('test-permissions', [
//    'middleware' => 'jwt.auth',
//    'uses' => 'SandboxController@testPermissions'
//]);

/*
 * protect schema
 */
$protectMethods = [
    'store' => ['store'],
    'index'   => ['index'],
    'show' => ['show'],
    'update' => ['update'],
    'destroy' => ['destroy'],
];

/*
 * work section
 */
Route::post('signup', 'SignController@up');
Route::post('signin', 'SignController@in');
Route::post('signout', 'SignController@out');
Route::get('check-email/{user?}', 'SignController@checkEmail');
Route::get('debug-token', 'SignController@debug');

Route::group([
    'middleware' => ['jwt.auth', 'acl', 'user_owner'],
    'is' => 'user|admin',
    'protect_alias' => 'user',
    'protect_methods' => $protectMethods,
], function () {
    Route::resource('user', 'UserController', ['except' => ['create', 'edit']]);
});

Route::group([
    'middleware' => ['jwt.auth', 'acl', 'exercise_owner'],
    'is' => 'user',
    'protect_alias' => 'exercise',
    'protect_methods' => $protectMethods,
], function () {
    Route::resource('exercise', 'ExerciseController', ['except' => ['create', 'edit']]);
});
Route::group([
    'middleware' => ['jwt.auth', 'acl', 'exercise_owner'],
    'is' => 'user',
    'protect_alias' => 'exercise_translation',
    'protect_methods' => $protectMethods,
], function () {
    Route::resource('exercise.translation', 'ExerciseTranslationController', ['only' => ['index', 'store', 'destroy']]);
});

Route::group([
    'middleware' => ['jwt.auth', 'acl'],
    'is' => 'user|admin',
    'protect_alias' => 'word',
    'protect_methods' => $protectMethods,
], function () {
    Route::resource('word', 'WordController', ['except' => ['create', 'edit']]);
});
Route::group([
    'middleware' => ['jwt.auth', 'acl'],
    'is' => 'user|admin',
    'protect_alias' => 'word_translation',
    'protect_methods' => $protectMethods,
], function () {
    Route::resource('word.translation', 'WordTranslationController', ['only' => ['index', 'store', 'destroy']]);
});

Route::group([
    'middleware' => ['jwt.auth', 'acl'],
    'is' => 'user|admin',
    'protect_alias' => 'translation',
    'protect_methods' => $protectMethods,
], function () {
    Route::resource('translation', 'TranslationController', ['except' => ['create', 'edit']]);
});

Route::group([
    'middleware' => ['jwt.auth', 'acl'],
    'is' => 'admin',
    'protect_alias' => 'position',
    'protect_methods' => $protectMethods,
], function () {
    Route::resource('position', 'PositionController', ['only' => ['index']]);
});


