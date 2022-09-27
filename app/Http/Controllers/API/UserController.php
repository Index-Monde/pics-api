<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
    public function updateProfileInformation(Request $request){
        $updateProfileData = Validator::make($request->all(),[
          'first_name' =>'required|min:2|max:255|string',
          'last_name' =>'required|min:2|max:255|string',
          'photo_url' => 'nullable|image|mimes:jpg,png,bmp',
        ]);
        if($updateProfileData->fails()){
           return $this->sendError('Update error',$updateProfileData->errors(),401);
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
