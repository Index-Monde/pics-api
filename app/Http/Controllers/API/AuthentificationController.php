<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthentificationController extends BaseController
{
    public function register(Request $request){
       $validator = Validator::make($request->all(),[
        'firstname' => 'required|max:55',
        'lastname' => 'required|max:55',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed'
       ]);
       if($validator->fails()){
         return $this->sendError('Validation error',$validator->errors()); 
       }
       $inputs = $request->all();
       $inputs['password'] = Hash::make($inputs['password']);
       $user = User::create($inputs);
       $results['token'] = $user->createToken('MyApp')->plainTextToken;
       $results['user'] = $user->firstname.' '.$user->lastname;
       return $this->sendResponse($results,'User register successifully');
    }
    public function login(Request $request){
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if(Auth::attempt($loginData)){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['user'] =  $user->firstname.' '.$user->lastname;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

}
