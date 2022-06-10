<?php
namespace App\Http\Controllers\API;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:255',
            'email'=>'required|email|max:255|unique:users,email',
            'password'=>'required|min:4',
            'c_password'=>'required|same:password'  
        ]);
        if($validator->fails())
        {
            return response()->json([ 
                'status'=>400, 
                'validation_errors'=>$validator->messages()
            ]);
        }
        else
        {
            $user = new User();
            $user->name= $request->name;
            $user->email= $request->email;
            $user->password=bcrypt($request->password);
            $user->save();
            $token= $user->createToken($user->email.'_Token')->plainTextToken;
            return response()->json([
                'username'=>$user->name,
                'status'=>200,
                'token'=>$token,
                'message'=>'User created succesfully'
            ]);  
        }
         
    }
    public function login(Request $request){
        $validator =Validator::make($request->all(),[
            'email'=>'required|max:255',
            'password'=>'required'
        ]);
        if($validator->fails()){
            return response()->json([
               'validation_errors'=>$validator->messages(),
            ]);
        }
        else{
            $user= User::Where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password , $user->password))
        {
        return response()->json([
            'status'=> 401,
            'message'=>'Wrong email or Password',
        ]);
        }
        else
        { 
            if($user->role_as == 1)// 1- admin acces
            {
                $role = 'admin';
                $token= $user->createToken($user->email.'_AdminToken',['server:admin'])->plainTextToken;
            }
            else
            {
                $role = '';
                $token=$user->createToken($user->email.'_Token',[''])->plainTextToken;
            }
            return response()->json([
                'status'=>200,
                'username'=>$user->name,
                'token'=>$token,
                'message'=>'Logged In Successfully',
                'role'=>$role,
            ]);
        }
      }    
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Logged Out Succesfully',
        ]);
    }
}