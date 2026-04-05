<?php

namespace App\Features\SystemSettings\Admin\Services;

use App\Core\BaseService;
use App\Features\SystemSettings\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use App\Core\Utils\FaviconUtil;

class SettingsService extends BaseService
{
    /**
     * Update general settings (Site Name, Maintenance Mode).
     */
    public function updateGeneralSettings(array $data)
    {
        if (isset($data['app_name'])) {
            Setting::set('app_name', $data['app_name']);
        }

        if (isset($data['is_maintenance'])) {
            Setting::set('is_maintenance', $data['is_maintenance'] ? '1' : '0');
        }

        if (isset($data['maintenance_duration'])) {
            Setting::set('maintenance_duration', $data['maintenance_duration']);
        }

        return true;
    }

    /**
     * Update the application logo.
     */
    public function updateLogo(UploadedFile $file)
    {
        $logoPath = public_path('logo.png');
        
        // Save to public root as logo.png
        $file->move(public_path(), 'logo.png');
        
        Setting::set('app_logo', '/logo.png');

        return true;
    }

    /**
     * Update the application favicon.
     */
    public function updateFavicon(UploadedFile $file)
    {
        $faviconPath = public_path('favicon.ico');
        
        // Convert to standard favicon format using our utility
        FaviconUtil::convert($file, $faviconPath);
        
        Setting::set('app_favicon', '/favicon.ico');

        return true;
    }
}
