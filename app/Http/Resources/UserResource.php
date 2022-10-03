<?php

namespace App\Http\Resources;

use App\Http\Requests\Auth\UserRegisterRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'photo_url' =>$this->photo_url,
            'type_of_subscription' => $this->type_of_subscription,
            'number_of_followers' =>$this->number_of_followers,
            'number_of_following' =>$this->number_of_following,
            'created_at' =>$this->created_at,
            'updated_at' =>$this->updated_at,
            'role' => $this->role
        ];
    }
}
