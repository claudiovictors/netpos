<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Slenix\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for this request.
     *
     * @return array<string, string|string[]>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Custom error messages (optional).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'Indique o seu endereço de email.',
            'email.email'       => 'Indique um endereço de email válido.',
            'password.required' => 'Indique a sua palavra-passe.',
        ];
    }
}