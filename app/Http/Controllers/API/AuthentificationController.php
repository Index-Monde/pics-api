<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthentificationController extends BaseController
{
    public function register(Request $request){
       $validator = Validator::make($request->all(),[
        'firstname' => 'required|max:255|string',
        'lastname' => 'required|max:255|string',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|min:8',
        'confirm_password'=>'required|same:password'
       ]);
       if($validator->fails()){
         return $this->sendError('Validation error',$validator->errors()); 
       }
       $inputs = $request->all();
       $inputs['password'] = Hash::make($inputs['password']);
       $inputs['confirm_password'] = Hash::make($inputs['confirm_password']);
       $user = User::create($inputs);
       $results['token'] = $user->createToken('MyApp')->plainTextToken;
       $results['user'] = $user;
       return $this->sendResponse($results,'User register successifully');
    }
    public function login(Request $request){
        $loginData = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:8'
        ]);
        if(Auth::attempt($loginData)){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['user'] =  $user;
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return $this->sendResponse([],'Logged out');
    }
    public function forgotPassword(Request $request){
        $request->validate([
            'email' => 'required|email|max:255'
        ]);
        $status = Password::sendResetLink($request->only('email'));
        if($status == Password::RESET_LINK_SENT){
            return $this->sendResponse([],$status);
        }else{
            throw ValidationException::withMessages([
				'email' => __($status)
			]);
        }

    }
    public function resetPassword(Request $request){
        $request->validate([
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:8|confirmed',
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'confirm_password', 'token'),
			function ($user, $password) use ($request) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		if($status == Password::PASSWORD_RESET) {
			return $this->sendResponse([],$status);
		} else {
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}
	}
}


