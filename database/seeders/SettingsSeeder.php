<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Features\SystemSettings\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'app_name'       => 'Laravel Feature Kit',
            'app_logo'       => '/logo.png',
            'app_favicon'    => '/favicon.ico',
            'is_maintenance'       => '0',
            'maintenance_duration' => '15 mins',
            'email_support'        => 'support@rifatxtra.com',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
        }
    }
}
