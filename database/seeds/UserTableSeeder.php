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

        $admin = User::create([
            'name' => 'root@root.test',
            'email' => 'root@root.test',
            'password' => \Hash::make('root@root.test'),
            'memory_count' => $settings['memory_count'],
            'reading_count' => $settings['reading_count'],
            'repeat_term' => $settings['repeat_term'],
        ]);
        $admin->assignRole('admin');

        $user = User::create([
            'name' => 'user@user.test',
            'email' => 'user@user.test',
            'password' => \Hash::make('user@user.test'),
            'memory_count' => $settings['memory_count'],
            'reading_count' => $settings['reading_count'],
            'repeat_term' => $settings['repeat_term'],
        ]);
        $user->assignRole('user');

        $user = User::create([
            'name' => 'Igor Demchenko',
            'email' => 'demchenko.igor88@gmail.com',
            'password' => \Hash::make('demchenko.igor88@gmail.com'),
            'memory_count' => $settings['memory_count'],
            'reading_count' => $settings['reading_count'],
            'repeat_term' => $settings['repeat_term'],
        ]);
        $user->assignRole(['admin', 'user']);

    }
}