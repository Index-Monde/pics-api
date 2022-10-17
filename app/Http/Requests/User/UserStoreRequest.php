<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 422)); 
    }
}
