<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;


class UserTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('users')->delete();

        $user = User::create([
            'name' => 'root',
            'email' => 'demchenko.igor88@gmail.com',
            'password' => \Hash::make('qwerty'),
        ]);

        $user->assignRole(['user', 'admin']);
    }
}