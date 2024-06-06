<?php

namespace App\Http\Requests\User;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateRequest extends FormRequest
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
            'name'       => ['required', 'string', 'max:255', 'min:3'],
            'identifier' => ['required', 'cpf_cnpj', 'unique:users,identifier'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'string', 'confirmed'],
            'type'       => ['required', new Enum(UserType::class)],
        ];
    }
}
