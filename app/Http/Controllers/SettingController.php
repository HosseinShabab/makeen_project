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
        return response()->json(['setting'=>$setting]);
    }

    public function update(SettingUpdateRequest $request)
    {
        $setting = Setting::where('id', 1)->update($request->toArray());
        return response()->json(['setting'=>$setting]);
    }

    public function addmedia(Request $request)
    {
        $setting = Setting::find(1);
        $setting = $setting->addMediaFromRequest('media')->toMediaCollection('logo', 'local');
        return response()->json(['setting'=>$setting]);
    }

    public function removemedia()
    {
        $setting = Setting::find(1);
        $setting = $setting->clearMediaCollection('logo');
        return response()->json(['setting'=>$setting]);
    }

    public function index()
    {   
        $setting = Setting::with("media")->find(1);
        return response()->json(['setting'=>$setting]);
    }
}
