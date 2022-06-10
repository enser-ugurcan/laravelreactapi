<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class FrontUserController extends Controller
{
    public function profile()
    {
        if(auth('sanctum')->check())
        { 
             $user_id=auth('sanctum')->user()->id; 
             $user = User::where('id', $user_id)->get();
                    return response()->json([
                        'status'=>200,
                        'user'=>$user,
                    ]);    
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>"Login to View User Data",
            ]);
        }  /*
        $user_id=auth('sanctum')->user(); 
        return response()->json([
            'status'=>200,
            'user'=>$user_id,
        ]);  */  
    
    }
    public function update(Request $request)
    {  
        $validator = Validator::make($request->all(),[
        'name'=>'required|max:191',
        'email'=>'required|max:191',
        'address'=>'max:191',
        'phone_number'=>'max:191',
        'birthday'=>'max:191',
        'image'=>'required|image|mimes:jpeg,pjg,png|max:2048',
        
    ]);
    if($validator->fails())
    {return response()->json([
        'status'=>422,
        'errors'=>$validator->messages(),
    ]);
    }
    else
    {
        $user_id=auth('sanctum')->user()->id;
        $user = User::where('id',$user_id)->get()->first();
        if($user)
        {
        $user->name =$request->input('name');
        $user->email = $request->input('email');
        $user->address = $request->input('address');
        $user->phone_number =$request->input('phone_number');
        $user->birthday = $request->input('birthday');
        if($request->hasFile('image'))
        {
            $path=$user->image;
            if(File::exists($path))
            {
                File::delete($path);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename=time() .'.'.$extension;
            $file->move('uploads/profile/', $filename);
            $user->image='uploads/profile/'.$filename;
        }
        $user->update();
        return response()->json([
            'status'=>200,
            'message'=>"User Update Successfuly",
        ]);   
        }
        
        if(!$user){
             return response()->json([
                'status'=>404,
                'message'=>"User Not Found",
            ]);
        }  
     }
  }
}
