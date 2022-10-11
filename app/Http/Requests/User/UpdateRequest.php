<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'role_id' => ['required','integer','max:3'],
            'profile_url' => ['nullable','image','mimes:png,jpg,bmp']
        ];
    }
}
