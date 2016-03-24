<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Acl\Models\Eloquent\Role;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('roles')->delete();

        Role::create(['name' => 'User', 'slug' => 'user', 'description' => 'user']);
        Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'admin']);
    }
}