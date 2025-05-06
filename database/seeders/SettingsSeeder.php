<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ["key" => "app_name", "value" => "WOKOYA Admin",],
            ["key" => "app_description", "value" => "WOKOYA Stater kits and admin generator",],
            ["key" => "app_favicon", "value" => '/uploads/2021/11/favicon.ico',],
            ["key" => "app_logo", "value" => '/uploads/2021/11/logo.png',],
            ["key" => "app_logo-compact", "value" => '/uploads/2021/11/logo-text.png',],
            ["key" => "app_brand-title", "value" => '/uploads/2021/11/logo-text.png',],
            ["key" => "app_copyright", "value" => "Copyright © <a href='/'>DUCOR</a> 2022",],
            //auth
            ["key" => "auth_disableRegistration", "value" => 1,],
            ["key" => "auth_rememberMe", "value" => 1,],
            ["key" => "auth_forgotPassword", "value" => 1,],
            ["key" => "auth_verifyEmail", "value" => 0,],
            ["key" => "notifications_signup_email", "value" => 0,]
        ];

        foreach ($settings as $s) {

            $check_has_settings = Settings::where('key', $s['key'])->first();
            if (!isset($check_has_settings)) {
                Settings::create($s);
            }
        }
    }
}
