<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingStoreRequest;
use App\Http\Requests\SettingUpdateRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function store(SettingStoreRequest $request)
    {
        $setting = Setting::create($request->toArray());
        return response()->json($setting);
    }

    public function update(SettingUpdateRequest $request)
    {
        $setting = Setting::find($request->setting_id);
        $setting->guarantors_count = $request->input('guarantors_count', $setting->guarantors_count);
        $setting->loans_count = $request->input('loans_count', $setting->loans_count);
        $setting->fund_name = $request->input('fund_name', $setting->fund_name);
        $setting->phone_number = $request->input('phone_number', $setting->phone_number);
        $setting->card_number = $request->input('card_number', $setting->card_number);
        $setting->description = $request->input('description', $setting->description);
        $setting->subscription = $request->input('subscription', $setting->subscription);
        $setting->save();
        return response()->json($setting);
    }

    public function addmedia(Request $request)
    {
        $setting = Setting::find(1);
        $setting = $setting->addMediaFromRequest('media')->collection('logo', 'local');
        return response()->json($setting);
    }

    public function removemedia()
    {
        $setting = Setting::find(1);
        $setting = $setting->clearMediaCollection('logo');
        return response()->json($setting);
    }

    public function index()
    {
        $setting = Setting::first();
        return response()->json($setting);
    }
}
