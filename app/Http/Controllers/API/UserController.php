<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UpdateRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\User\UserStoreRequest;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        if(count($users) > 0){

          return $this->sendResponse(UserResource::collection($users),"All users");
        }
       return $this->sendError('Users not exist',[],404);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request\User\UserStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $user = User::create($request->all());
        return $this->sendResponse(new UserResource($user),'User created',201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if($user->exists()){
            return $this->sendResponse(new UserResource($user),'User found'); 
        }
        return $this->sendError('User error',['error'=>'Not found user'],404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request\User\UpdateRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user = $request->user();
        if($request->hasFile('profile_url')){
            if($user->profile_url){
               $old_path = public_path().'uploads/profile_images/'.$user->profile_url;
               if(File::exists($old_path)){
                   File::delete($old_path);
               }
            }
            $image_name = 'profile-image-'.time().'.'.$request->profile_url->extension();
            $request->profile_url->move(public_path('/uploads/profile-images'),$image_name);
        }else{
          $image_name = $user->profile_url;
        }
       $user->update([
          'first_name' => $request->firstname,
          'last_name' => $request->lastname,
          'profile_url'=> $image_name,
          'role_id' => $request->role_id
       ]);
       return $this->sendResponse($user,'Profile successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->sendResponse([],'Delete user matched');
    }
    public function updatePassword(Request $request){
         $data = Validator::make($request->all(),[
          'new_password'=> 'required|min:8|confirmed',
          'old_password'=> 'required|min:8'
         ]);
         if($data->fails()){
           return $this->sendError('Validation password error',$data->errors(),422);
         }
         $user = $request->user();
         if(Hash::check($request->old_password,$user->password)){
             $user->update([
                'password' => Hash::make($request->new_password)
             ]);
             return $this->sendResponse([],'Password successfully updated');
         }
         return $this->sendError('Old password error',['error'=>'Old password does not matched'],400);
    }
}
