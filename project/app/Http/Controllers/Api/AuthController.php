<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
  
    public function register(Request $request){
        $validator=Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

      
      $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Register successfully',
            'token'=>$token,
            'user_id'=>$user->id
           
        ], 201);
    }



    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Logout successfully!'
            ]);
}


public function login(Request $request){
    $validator= Validator::make($request->all(),[
     'email'=>'required|string|email|max:255',
     'password'=>'required|string|min:8'
    ]);
    if($validator->fails()){
     return response()->json(['errors'=>$validator->errors()],422);
    }

    $user=User::where('email',$request->email)->first();
    if(!$user || !Hash::check($request->password,$user->password)){
     return response()->json(['message'=>'Wrong login information']);
    }

    $token=$user->createToken('auth_token')->plainTextToken;
    return response()->json([  'status' => 'success','message'=>'Login successfully', 'token'=>$token,'user_id'=>$user->id],200);
 }

public function changePass(Request $request,$id){
    $validator=Validator::make(request()->all(),[
       
        'old_password' => 'required|string|min:8',
        'new_password'=>'required|string|min:8',
    ]);
    if($validator->fails()){
        return response()->json(["success"=>false,"error"=>$validator->errors()->first()]);
    }
    $user=User::findOrFail($id);
    
    if(!Hash::check($request->input('old_password'),$user->password)){
        return response()->json(["success"=>false,"error"=>"Wrong password!"]);
    }
    
    $user->password=Hash::make($request->input('new_password'));
    $user->save();
    return response()->json(["success"=>true,"message"=>"Change password successfully!"]);
   

}


}