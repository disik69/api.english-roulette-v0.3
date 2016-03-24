<?php

use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Models\Eloquent\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('permissions')->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $exercisePermission = Permission::create([
            'name'        => 'exercise',
            'slug'        => [
                'index' => true,
                'store' => true,
                'show' => true,
                'update' => true,
                'destroy' => true,
            ],
            'description' => 'exercise',
        ]);

        $wordPermission = Permission::create([
            'name'        => 'word',
            'slug'        => [
                'index' => true,
                'store' => true,
                'show' => true,
                'update' => true,
                'destroy' => true,
            ],
            'description' => 'word',
        ]);
        $userWordPermission = Permission::create([
            'name'        => 'word.user',
            'slug'        => [
                'show' => false,
                'update' => false,
                'destroy' => false,
            ],
            'inherit_id' => $wordPermission->getKey(),
            'description' => 'word',
        ]);

        $translationPermission = Permission::create([
            'name'        => 'translation',
            'slug'        => [
                'index' => true,
                'store' => true,
                'show' => true,
                'update' => true,
                'destroy' => true,
            ],
            'description' => 'translation',
        ]);
        $userTranslationPermission = Permission::create([
            'name'        => 'translation.user',
            'slug'        => [
                'show' => false,
                'update' => false,
                'destroy' => false,
            ],
            'inherit_id' => $translationPermission->getKey(),
            'description' => 'translation',
        ]);

        $positionPermission = Permission::create([
            'name'        => 'position',
            'slug'        => [
                'index' => true,
            ],
            'description' => 'position',
        ]);

        Role::where('slug', 'user')->first()->assignPermission([$exercisePermission, $userWordPermission, $userTranslationPermission, $positionPermission]);
        Role::where('slug', 'admin')->first()->assignPermission([$wordPermission, $translationPermission, $positionPermission]);
    }
}
