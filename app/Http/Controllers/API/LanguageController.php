<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    public function addLanguage(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name'=>'required|max:191',
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
            $language = new Language;
            $language->name = $request->input('name');
            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename=time() .'.'.$extension;
                $language->image='uploads/languageimg/'.$filename;
            }
            $language->save();
            return response()->json([
                'status'=>200,
                'message'=>"Language added",
            ]);
        }
    }
    public function language()
    {
        $languages = Language::all();
        return response()->json([
            'status'=>200,
            'languages'=>$languages
        ]);

    }
    public function index()
    {
        $languages = Language::all();
        return response()->json([
            'status'=>200,
            'languages'=>$languages
        ]);

    }
    public function edit($id)
    {
        $languages = Language::find($id);
        if($languages)
        {
            return response()->json([
                'status'=>200,
                'languages'=>$languages
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No languages Id Found',
            ]);
        }
    }
    public function delete($id)
    {
            $languages = Language::find($id);
            if($languages)
            {
                $languages->delete();
                return response()->json([
                    'status'=>200,
                    'message'=>'Language Deleted Successfully',
                ]);
            }   
            else
            {
                return response()->json([
                    'status'=>200,
                    'message'=>'No languages ID Found',
                ]);
            } 
    }
}
