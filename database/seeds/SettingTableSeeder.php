<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('settings')->delete();

        Setting::create(['key' => 'reading_count', 'value' => 10]);
        Setting::create(['key' => 'memory_count', 'value' => 10]);
        Setting::create(['key' => 'repeat_term', 'value' => 7]);
    }
}
