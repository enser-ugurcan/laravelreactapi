<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Setting;

class SettingController extends Controller
{
     public function setting(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name'=>'required|max:191',
            
        ]);
            $language = new Setting;
            $language->title = $request->input('title');
            $language->keyword = $request->input('keyword');
            $language->description = $request->input('description');
            $language->company = $request->input('company');
            $language->address = $request->input('address');
            $language->phone = $request->input('phone');
            $language->fax = $request->input('fax');
            $language->email = $request->input('email');
            $language->smtpserver = $request->input('smtpserver');
            $language->smtpemail = $request->input('smtpemail');
            $language->smtppassword = $request->input('smtppassword');
            $language->smtpport = $request->input('smtpport');
            $language->facebook = $request->input('facebook');
            $language->instagram = $request->input('instagram');
            $language->twitter = $request->input('twitter');
            $language->youtube = $request->input('youtube');
            $language->aboutus = $request->input('aboutus');
            $language->references = $request->input('references');
            $language->save();
            return response()->json([
                'status'=>200,
                'message'=>"Setting added",
            ]);
    
    }
    public function index()
    {
        $setting = Setting::all();
        return response()->json([
            'status'=>200,
            'setting'=>$setting
        ]);

    }
    public function edit($id)
    {
        $setting = Setting::find($id);
        if($setting)
        {
            return response()->json([
                'status'=>200,
                'setting'=>$setting
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No setting Id Found',
            ]);
        }
    }
    public function updateSetting(Request $request , $id)
    { 
        $validator = Validator::make($request->all(),[
        'email'=>'required|max:155',
  
    ]);
    if($validator->fails())
    {
        return response()->json([
            'status'=>422,
            'errors'=>$validator->messages(),
        ]);
    }
    else
    {
        $setting = Setting::find($id);
        if($setting)
        {
            $setting->title = $request->input('title');
            $setting->keyword = $request->input('keyword');
            $setting->description = $request->input('description');
            $setting->company = $request->input('company');
            $setting->address = $request->input('address');
            $setting->phone = $request->input('phone');
            $setting->fax = $request->input('fax');
            $setting->email = $request->input('email');
            $setting->smtpserver = $request->input('smtpserver');
            $setting->smtpemail = $request->input('smtpemail');
            $setting->smtppassword = $request->input('smtppassword');
            $setting->smtpport = $request->input('smtpport');
            $setting->facebook = $request->input('facebook');
            $setting->instagram = $request->input('instagram');
            $setting->twitter = $request->input('twitter');
            $setting->youtube = $request->input('youtube');
            $setting->aboutus = $request->input('aboutus');
            $setting->references = $request->input('references');      
            $setting->update();

            return response()->json([
                'status'=>200,
                'message'=>"Setting Update Successfuly",
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>"Product Not Found",
            ]);
        }
     }
   
    }
}
