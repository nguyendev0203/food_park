<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.setting.index');
    }

    public function UpdateGeneralSetting(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required', 'max:255'],
            'site_email' => ['nullable', 'max:255'],
            'site_phone' => ['nullable', 'max:255'],
            'site_default_currency' => ['required', 'max:4'],
            'site_currency_icon' => ['required', 'max:4'],
            'site_currency_icon_position' => ['required', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        app(SettingsService::class)->clearCachedSetting();

        toastr()->success('Updated Successfully!');

        return redirect()->back();
    }
}
