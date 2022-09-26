<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthentificationController extends BaseController
{
    public function register(UserRegisterRequest $request){
        $user = User::create($request->all());
        $sucess['token'] = $user->createToken('MyApp')->plainTextToken;
        $sucess['user'] = new UserResource($user);
        return $this->sendResponse($sucess,'User register successifully');
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
            return $this->sendError('User login error', ['error'=>'User not exits'],404);
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
           return $this->sendError('Reset link error',['error'=>'Error to send reset link'],422);
        }

    }
    public function resetPassword(Request $request){
        $request->validate([
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:8|confirmed',
		]);

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
    public function updateProfileInformation(Request $request){
              $updateProfileData = Validator::make($request->all(),[
                'first_name' =>'required|min:2|max:255|string',
                'last_name' =>'required|min:2|max:255|string',
                'photo_url' => 'nullable|image|mimes:jpg,png,bmp',
              ]);
              if($updateProfileData->fails()){
                 return $this->sendError('Update error',$updateProfileData->errors(),404);
              }
              $user = $request->user();
              if($request->hasFile('photo_url')){
                  if($user->photo_url){
                     $old_path = public_path().'uploads/profile_images/'.$user->photo_url;
                     if(File::exists($old_path)){
                         File::delete($old_path);
                     }
                  }
                  $image_name = 'profile-image-'.time().'.'.$request->photo_url->extension();
                  $request->photo_url->move(public_path('/uploads/profile-images'),$image_name);
              }else{
                $image_name = $user->photo_url;
              }
             $user->update([
                'first_name' => $request->firstname,
                'last_name' => $request->lastname,
                'photo_url'=> $image_name,
             ]);
             return $this->sendResponse($user,'Profile successfully updated');

    }
    public function updatePassword(){

    }
}


