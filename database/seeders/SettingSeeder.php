<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'global_quota'],
            ['value' => 10485760]
        );

        Setting::updateOrCreate(
            ['key' => 'banned_extensions'],
            ['value' => 'exe,bat,js,php,sh']
        );
    }
}
