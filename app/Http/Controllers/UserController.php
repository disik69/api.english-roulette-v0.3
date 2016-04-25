<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo('user index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo('user store');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user->view());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = \Validator::make(
            \Request::all(),
            [
                'name' => 'required|max:100',
                'email' => [
                    'required',
                    'max:60',
                    'regex:/^.+@(\w([\w-]*\w)?\.)+\w+$/',
                    'unique:users,email,' . $user->id,
                ],
                'memory_count' => 'required|integer|min:1|max:100',
                'reading_count' => 'required|integer|min:1|max:100',
                'lesson_size' => 'required|integer|min:1|max:100',
                'repeat_term' => 'required|integer|min:1|max:365',
            ]
        );

        if ($validator->passes()) {
            extract(\Request::all());

            $user->name = $name;
            $user->email = $email;
            $user->memory_count = $memory_count;
            $user->reading_count = $reading_count;
            $user->repeat_term = $repeat_term;
            $user->lesson_size = $lesson_size;

            $user->save();

            $response = response('User has been changed.');
        } else {
            $response = response()->json(['errors' => $validator->messages()->all()], 400);
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        echo('destroy user');
    }
}
