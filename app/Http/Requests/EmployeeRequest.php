<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Slenix\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            // 'field' => 'required|string|max:255',
        ];
    }

    /**
     * Custom error messages (optional).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [];
    }
}