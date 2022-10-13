<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => ['required','string','max:255','min:2'],
            'last_name' => ['required','string','max:255','min:2'],
            'email' => ['email','required','unique:users'],
            'password'=>['required','min:8'],
            'role_id' => ['required','integer','max:3'],
            'setting_id'=> ['required','integer'],
            'current_subscription_status' => ['required','string']
        ];
    }
}
