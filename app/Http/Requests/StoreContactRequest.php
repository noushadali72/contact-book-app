<?php

namespace App\Http\Requests;

use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Override;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:contacts,email',
            ],

            'phone' => 'required|string|max:20|unique:contacts,phone|regex:/^\+[1-9]\d{1,14}$/',
            'address' => 'required|string|max:255',
            'notes' => 'required|string',

            'group_id' => [
                'required',
                'integer',
                Rule::exists('groups', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required!',
            'email.required' => 'Email field is required!',
            'email.email' => 'Please enter a valid email address!',
            'email.unique' => 'Email already exists!',
            'phone.required' => 'Phone number field is required!',
            'phone.unique' => 'Phone number already exists!',
            'phone.regex'=>"Phone no must be valid!",
            'address.required' => 'Address field is required!',
            'notes.required' => 'Notes field is required!',
            'group_id.required' => 'Please select a group!',
            'group_id.exists' => 'The selected group is invalid.',
        ];
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "status" => "error",
            "message"=>"Fill the Required fields with valid data!",
            "errors"=>$validator->errors()
        ],422));
    }
}