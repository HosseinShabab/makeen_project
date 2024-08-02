<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function store(Request $request)
    {
        $setting = Setting::create($request->toArray());
        return response()->json($setting);
    }

    public function update(){

    }

    public function addmedia(Request $request)
    {
        $type = $request->type;
        $typable_id = $request->typable_id;
        if ($request->user()->hasRole("admin")) {
            $type = "admin";
            $typable_id = $request->user()->id;
        }
        if ($type == 'setting') {
            $setting = Setting::find($typable_id);
        }
        $setting = $setting->addMediaFromRequest('media')->toMediaCollection("$request->collection", 'local');
        return response()->json($setting);
    }

    public function removemedia(Request $request)
    {
        $setting = $request->setting()->MediaCollections('logo')->destroy();
        return response()->json($setting);
    }

    public function index(Request $request){
        $setting = Setting::get();
        return response()->json($setting);
    }
}
