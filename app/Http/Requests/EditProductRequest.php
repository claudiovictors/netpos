<?php

declare(strict_types=1);

namespace App\Http\Requests;

/**
 * Validates the "edit product" request.
 *
 * Reuses the create rules and adjusts the `unique` checks so the
 * current record is ignored.
 */
class EditProductRequest extends ProductRequest
{
    /**
     * Validation rules for this request.
     *
     * @return array<string, string|string[]>
     */
    public function rules(): array
    {
        $id = (int) $this->input('id');

        return array_merge(parent::rules(), [
            'id'      => 'required|integer|exists:products,id',
            'code'    => "required|string|max:50|unique:products,code,{$id}",
            'barcode' => "nullable|regex:/^[0-9]{8}[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?$/|unique:products,barcode,{$id}",
        ]);
    }

    /**
     * Custom error messages (inherits the parent messages).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'id.required' => 'Produto não identificado.',
            'id.exists'   => 'Produto não encontrado.',
        ]);
    }
}