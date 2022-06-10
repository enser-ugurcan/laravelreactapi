<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Mail;


class ForgotController extends Controller
{
    
   public function forgot(ForgotPasswordRequest $request)
   {
       $email = $request->email;
       if(User::where('email', $email)->doesntExist()){
           return response([
               'status'=>400,
               'message'=>'User doesn\t exist'
           ]);
       }

       $token=Str::random(10);
       DB::table('password_resets')->insert(['email'=>$email,
       'token'=>$token, 'created_at'=>Carbon::now()]);

       $passwordReset = DB::table('password_resets')->where
       ('token',$token)->first();

       Mail::send('auth.password.reset',['token' => $token],
       function($message) use ($request){
           $message->to('befa@gmail.com','Befa Shopping')->subject('Reset Password Notification');
        //    $message->from($request->email);
        //    $message->to('befa@gmail.com');
        //    $message->subject('Reset Password Notification');
       });
       return response([
           'status'=>200,
           'message'=>'Check your email',
           'token'=>$passwordReset->token,
           'created_at'=>$passwordReset->created_at
       ]);

   }
   public function reset(ResetPasswordRequest $request)
   {
    $validator = Validator::make($request->all(),[
        'token'=>'required',
        'password'=>'required|min:5',
        'c_password'=>'required'
    ]);
    if($validator->fails())
    {
        return response()->json([
            'status'=>422,
            'errors'=>$validator->messages(),
        ]);
    }
       $token = $request->token;
       if(!$passwordreset = DB::table('password_resets')->where('token', $token)->first()){
           return response([
               'status'=>400,
               'message'=>'Invalid Token',
           ]);
       }
       $user = User::where('email',$passwordreset->email)->first();
       if(!$user){
           return respnse([
               'status'=>400,
               'message'=>'User doesn\t exits' 
           ]);
       }
      User::where('email',$passwordreset->email)->update([
          'password'=>Hash::make($request->password)
      ]);
       return response([
           'status'=>200,
           'message'=>'Password Reset Successfully',
           'password'=>$request->password 
       ]);
   }
 
}
