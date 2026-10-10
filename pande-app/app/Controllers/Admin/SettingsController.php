<?php
/**
 * Admin Store Settings Controller
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Session;
use App\Models\Setting;

class SettingsController extends BaseController {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(Request $request): void {
        $settingsGrouped = Setting::getAllGrouped();

        $this->render('admin.settings.index', [
            'pageTitle' => 'Store Settings - Admin',
            'settings' => $settingsGrouped,
        ], 'admin');
    }

    public function update(Request $request): void {
        $data = $request->all();

        // Update settings in store_settings table
        foreach ($data as $key => $value) {
            if ($key === 'csrf_token') continue;
            Setting::set($key, trim((string)$value));
        }

        Logger::audit('settings_updated', 'settings', null, 'Store settings updated by admin');
        Session::flash('success', 'Store configuration updated successfully.');
        $this->redirect(APP_URL . '/admin/settings');
    }
}
