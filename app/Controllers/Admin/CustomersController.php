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
 *
 * Listagem com paginação e pesquisa; criação, actualização e
 * eliminação via JSON para consumo AJAX.
 */
class CustomersController
{
    /**
     * Mostra a página de listagem de clientes (com paginação e pesquisa).
     *
     * Query: page, per_page (10|20|50|100), search (nome, NIF, telefone, email).
     * Pedidos AJAX devolvem apenas o partial da tabela.
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

        if ($search = trim((string) $req->query('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nif', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate($perPage);

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
     * Actualiza um cliente existente (apenas do tenant actual).
     *
     * @param  EditCustomerRequest $req
     * @param  Response            $res
     * @return mixed Resposta JSON (404 se não existir).
     */
    public function update(EditCustomerRequest $req, Response $res)
    {
        $data     = $req->validated();
        $customer = $this->findOwnedCustomer((int) ($data['id'] ?? 0));

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
     * Elimina um cliente existente (apenas do tenant actual).
     *
     * @param  Request  $req
     * @param  Response $res
     * @param  array    $param Parâmetros de rota (ex.: ['id' => '12']).
     * @return mixed Resposta JSON (404 se não existir).
     */
    public function delete(Request $req, Response $res, array $param)
    {
        $customer = $this->findOwnedCustomer((int) ($param['id'] ?? 0));

        if (!$customer) {
            return $res->json([
                'success' => false,
                'message' => 'Cliente não encontrado.',
            ], 404);
        }

        $customer->delete();

        return $res->json([
            'success' => true,
            'message' => 'Cliente eliminado com sucesso.',
        ]);
    }

    /**
     * Localiza um cliente pelo ID restringindo ao tenant do utilizador actual.
     *
     * @param  int $id
     * @return Customer|null
     */
    private function findOwnedCustomer(int $id): ?Customer
    {
        if ($id <= 0) {
            return null;
        }

        $user = auth()->user();
        if (!$user) {
            return null;
        }

        return Customer::where('id', $id)
            ->where('tenant_id', $user->tenant_id)
            ->first();
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