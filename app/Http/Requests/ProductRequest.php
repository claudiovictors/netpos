<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Slenix\Http\FormRequest;

/**
 * Validates the "create product" request.
 *
 * Keep these rules in sync with the client-side rules in
 * assets/admin/js/product.js.
 */
class ProductRequest extends FormRequest
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
            'tenant_id'   => 'required|integer',
            'name'        => 'required|string|min:2|max:255',
            'code'        => 'required|string|max:50|unique:products,code',
            'barcode' => 'nullable|regex:/^[0-9]{8}[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?$/|unique:products,barcode',
            'category_id' => 'required|integer|exists:categories,id',
            'cost_price'  => 'nullable|numeric|min:0',
            'sale_price'  => 'required|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'min_stock'   => 'nullable|integer|min:0',
            'status'      => 'required|in:active,inactive',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tenant_id.integer'    => 'O id do tenant é obrigatório.',
            'name.required'        => 'O nome do produto é obrigatório.',
            'name.min'             => 'O nome deve ter pelo menos 2 caracteres.',
            'name.max'             => 'O nome não pode exceder 255 caracteres.',
            'code.required'        => 'O código interno é obrigatório.',
            'code.max'             => 'O código não pode exceder 50 caracteres.',
            'code.unique'          => 'Já existe um produto com este código.',
            'barcode.regex'        => 'Código de barras inválido (8 a 14 dígitos).',
            'barcode.unique'       => 'Este código de barras já está registado.',
            'category_id.required' => 'Seleccione uma categoria.',
            'category_id.exists'   => 'A categoria seleccionada não existe.',
            'cost_price.numeric'   => 'Preço de custo inválido.',
            'sale_price.required'  => 'O preço de venda é obrigatório.',
            'sale_price.numeric'   => 'Preço de venda inválido.',
            'stock.integer'        => 'O stock deve ser um número inteiro ≥ 0.',
            'min_stock.integer'    => 'O stock mínimo deve ser um número inteiro ≥ 0.',
            'status.in'            => 'Estado inválido.',
            'description.max'      => 'A descrição não pode exceder 1000 caracteres.',
        ];
    }
}