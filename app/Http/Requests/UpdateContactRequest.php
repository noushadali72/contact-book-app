<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Override;

class UpdateContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       
      return [
            'name' => 'required|string|min:2|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                // 'unique:contacts,email',
                Rule::unique('contacts')->ignore($this->input('id'))
            ],

            'phone' => 'required|string|max:20|regex:/^\+[1-9]\d{1,14}$/',
            'address' => 'required|string|max:255',
            'notes' => 'required|string',

            'group_id' => [
                'required',
                'integer',
                Rule::exists('groups', 'id'),
            ],
        ];
    }
    public function messages():array{
         return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'Email already exists.',
            'phone.required' => 'Phone number is required.',
            'phone.regex'=>"Phone no must be valid!",
            'address.required' => 'Address is required.',
            'notes.required' => 'Notes are required.',
            'group_id.required' => 'Please select a group.',
            'group_id.exists' => 'The selected group is invalid.',
        ];
    }
    #[Override]
    protected function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
            'status'=>'error',
            'message'=>'Please fill valid data!',
            'errors'=>$validator->errors()
       ],422));
    }
}
