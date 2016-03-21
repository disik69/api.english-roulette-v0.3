<?php

namespace App\Http\Controllers;

use App\User;
use App\Setting;

class SignController extends Controller
{
    public function up()
    {
        $validator = \Validator::make(
            \Request::all(),
            [
                'name' => 'required|max:100',
                'email' => [
                    'required',
                    'max:60',
                    'regex:/^.+@(\w([\w-]*\w)?\.)+\w+$/',
                    'unique:users',
                ],
                'password' => 'required|min:6|max:100',
                'captcha' => 'required|captcha',
            ]
        );

        if ($validator->passes()) {
            extract(\Request::all());
            $settings = Setting::getList();

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => \Hash::make($password),
                'memory_count' => $settings['memory_count'],
                'reading_count' => $settings['reading_count'],
                'repeat_term' => $settings['repeat_term'],
            ]);

            $user->assignRole('user');

            $response = response()->json(['token' => \JWTAuth::fromUser($user)]);
        } else {
            $response = response()->json(['errors' => $validator->messages()->all()], 400);
        }

        return $response;
    }

    public function in()
    {
        $credentials = \Request::only('email', 'password');

        if ($token = \JWTAuth::attempt($credentials)) {
            $response = response()->json(['token' => $token]);
        } else {
            $response = response()->json(['errors' => ['The email/password is invalid.']], 400);
        }

        return $response;
    }

    public function checkEmail()
    {
        $validator = \Validator::make(
            \Request::all(),
            [
                'email' => [
                    'required',
                    'max:60',
                    'regex:/^.+@(\w([\w-]*\w)?\.)+\w+$/',
                    'unique:users',
                ],
            ]
        );

        if ($validator->passes()) {
            $response = response()->json();
        } else {
            $response = response()->json(['errors' => $validator->messages()->all()], 400);
        }

        return $response;
    }

    public function debug()
    {
        $pasport['name'] = 'guest';
        $pasport['roles'] = ['guest'];

        try {
            $user = \JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $user = null;
        }

        if ($user) {
            $pasport['name'] = $user->name;
            $pasport['roles'] = array_values($user->getRoles());
        }

        return response()->json($pasport);
    }
}