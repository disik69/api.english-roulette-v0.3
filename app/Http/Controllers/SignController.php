<?php

namespace App\Http\Controllers;

use App\User;
use App\Setting;
use Tymon\JWTAuth\Exceptions\JWTException;

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

            try {
                $response = response()->json(['token' => \JWTAuth::fromUser($user)]);
            } catch (JWTException $e) {
                $response = response()->json(['error' => 'Could not create token'], 500);
            }
        } else {
            $response = response()->json(['error' => 'Invalid fields'], 400);
        }

        return $response;
    }

    public function in()
    {
        $credentials = \Request::only('email', 'password');

        try {
            if ($token = \JWTAuth::attempt($credentials)) {
                $response = response()->json(['token' => $token]);
            } else {
                $response = response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            $response = response()->json(['error' => 'Could not create token'], 500);
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
            $response = response()->json(['message' => 'Email free']);
        } else {
            $response = response()->json(['message' => 'Email busy'], 400);
        }

        return $response;
    }

    public function debug()
    {
        return response()->json(['message' => 'OK']);
    }
}