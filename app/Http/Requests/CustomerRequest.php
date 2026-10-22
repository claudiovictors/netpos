<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Slenix\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name'    => 'required|string|max:255',
            'nif'     => 'nullable|string|max:50',
            'phone'   => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do cliente é obrigatório.',
            'name.max'      => 'O nome não pode exceder 255 caracteres.',
            'email.email'   => 'O formato do email é inválido.',
        ];
    }
}