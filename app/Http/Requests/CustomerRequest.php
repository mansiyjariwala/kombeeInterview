<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255|alpha_num',
                'email' => 'required|string|email|max:255|unique:users',
                'contact_number' => 'required|string|max:20',
                'postcode' => 'required|string|max:10',
                'state' => 'required|exists:states,id',
                'city' => 'required|exists:cities,id',
                'password' => 'required|string|min:8|confirmed',
                'hobbies' => 'required|array|min:1',
                'hobbies.*' => 'string',
                'gender' => 'required|in:Male,Female',
                'files.*' => 'mimes:jpeg,png,pdf|max:2048', // Example file validation (adjust as needed)
                'roles' => 'required|array|min:1',
                'roles.*' => 'exists:roles,id',
            ];
    }
}
