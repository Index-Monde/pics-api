<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Requests\Auth\UserLoginRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;

class AuthentificationController extends BaseController
{
    public function register(UserRegisterRequest $request){
        $data = [
          'email' => $request->email,
          'password'=> Hash::make($request->password),
          'last_name'=>$request->last_name,
          'first_name' => $request->first_name,
        ];
        $user = User::create($data);
        $sucess['token'] = $user->createToken('MyApp')->plainTextToken;
        $sucess['user'] = new UserResource($user);
        return $this->sendResponse($sucess,'User register successifully',201);
    }
    public function login(UserLoginRequest $request){
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $user = User::where('email',$data['email'])->first();
         if($user){
              if($user->password === $data['password']){
                $sucess['user'] = new UserResource($user);
                $sucess['token'] = $user->createToken('MyApp')->plainTextToken;
                return $this->sendResponse($sucess,'User login successifully');
              }else{
                return $this->sendError('User login error', ['error'=>'Password incorrect'],401);
              }
         }else{ 
            return $this->sendError('User login error', ['error'=>'User not exists'],404);
        }
}
       
    public function logout(){
        auth()->user()->tokens()->delete();
        return $this->sendResponse([],'Logged out');
    }
    public function forgotPassword(ForgotPasswordRequest $request){
        $status = Password::sendResetLink($request->only('email'));
        if($status == Password::RESET_LINK_SENT){
            return $this->sendResponse([],$status);
        }else{
           return $this->sendError('Reset link error',['error'=>'Error to send reset link'],422);
        }

    }
    public function resetPassword(ResetPasswordRequest $request){
		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
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


