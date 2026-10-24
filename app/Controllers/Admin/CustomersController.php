<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Customer;
use Slenix\Http\Request;
use Slenix\Http\Response;
use Slenix\Supports\Libraries\Str;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\EditCustomerRequest;

/**
 * Controlador de gestão da base de dados de clientes.
 */
class CustomersController
{
    /**
     * Mostra a página de listagem de clientes (com paginação e pesquisa).
     *
     * @param  Request  $req
     * @param  Response $res
     * @return mixed
     */
    public function index(Request $req, Response $res)
    {
        $user = auth()->user();
        if (!$user) {
            return $res->redirect(route('login'));
        }

        $perPage = (int) $req->query('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100], true) ? $perPage : 20;

        $query = Customer::where('tenant_id', $user->tenant_id)
            ->orderBy('id', 'desc');

        // Pesquisa rápida por nome, NIF, telefone ou email
        if ($search = trim((string) $req->query('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nif', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate($perPage);

        // Pedido AJAX → devolve apenas o HTML parcial da tabela + paginação
        if ($req->getHeader('X-Requested-With') === 'XMLHttpRequest') {
            return view('partials.customers-table', [
                'customers' => $customers,
            ]);
        }

        return view('admin.customers', [
            'customers' => $customers,
        ]);
    }

    /**
     * Cria um novo cliente.
     *
     * @param  CustomerRequest $req
     * @param  Response        $res
     * @return mixed Resposta JSON (201 em sucesso).
     */
    public function store(CustomerRequest $req, Response $res)
    {
        $customer = Customer::create($this->normalize($req->validated()));

        return $res->json([
            'success' => true,
            'message' => 'Cliente criado com sucesso.',
            'data'    => $customer,
        ], 201);
    }

    /**
     * Actualiza um cliente existente.
     *
     * @param  EditCustomerRequest $req
     * @param  Response            $res
     * @return mixed Resposta JSON (404 se o cliente não existir).
     */
    public function update(EditCustomerRequest $req, Response $res)
    {
        $data     = $req->validated();
        $customer = Customer::find((int) ($data['id'] ?? 0));

        if (!$customer) {
            return $res->json([
                'success' => false,
                'message' => 'Cliente não encontrado.',
            ], 404);
        }

        $customer->update($this->normalize($data));

        return $res->json([
            'success' => true,
            'message' => 'Cliente actualizado com sucesso.',
        ]);
    }

    /**
     * Normaliza e sanitiza os dados antes de persistir.
     *
     * @param  array<string, mixed> $in
     * @return array<string, mixed>
     */
    private function normalize(array $in): array
    {
        $user    = auth()->user();
        $email   = Str::escape((string) ($in['email'] ?? ''));
        $phone   = Str::escape((string) ($in['phone'] ?? ''));
        $nif     = Str::escape((string) ($in['nif'] ?? ''));
        $address = Str::escape((string) ($in['address'] ?? ''));

        return [
            'tenant_id' => $user ? (int) $user->tenant_id : 0,
            'name'      => Str::escape((string) ($in['name'] ?? '')),
            'email'     => $email !== '' ? $email : null,
            'phone'     => $phone !== '' ? $phone : null,
            'nif'       => $nif !== '' ? $nif : null,
            'address'   => $address !== '' ? $address : null,
        ];
    }
}