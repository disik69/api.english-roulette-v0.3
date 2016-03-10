<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Setting;


class UserTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('users')->delete();

        $settings = Setting::getList();

        $user = User::create([
            'name' => 'root',
            'email' => 'demchenko.igor88@gmail.com',
            'password' => \Hash::make('qwerty'),
            'memory_count' => $settings['memory_count'],
            'reading_count' => $settings['reading_count'],
            'repeat_term' => $settings['repeat_term'],
        ]);

        $user->assignRole(['user', 'admin']);

    }
}