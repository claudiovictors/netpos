<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Slenix\Http\FormRequest;

class EditEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string|string[]>
     */
    public function rules(): array
    {
        return [
            'id'       => 'required|integer',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'nullable|string|max:50',
            'status'   => 'nullable|string|in:active,inactive',
            'password' => 'nullable|string|min:6',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.required'    => 'ID do funcionário inválido.',
            'name.required'  => 'O nome do funcionário é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email'    => 'Indique um endereço de email válido.',
            'password.min'   => 'A palavra-passe deve ter pelo menos 6 caracteres.',
        ];
    }
}