<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string|max:30|unique:roles',
                    'description' => 'required'                  
                ];
            case 'PUT':
            case 'PATCH':
                $roleId = $this->route('id'); 
                return [
                    'name' => 'required|string|max:30|unique:roles,name,' . $roleId,
                    'description' => 'required'                  
                ];
            default:
            return [];
        }
    }
}
