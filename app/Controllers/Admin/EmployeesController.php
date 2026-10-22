<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Requests\EditEmployeeRequest;
use App\Http\Requests\EmployeeRequest;
use App\Models\User;
use Slenix\Http\Request;
use Slenix\Http\Response;

/**
 * Controlador de gestão de funcionários (utilizadores do sistema).
 */
class EmployeesController
{
    /**
     * Mostra a listagem de funcionários.
     *
     * @param  Request  $req
     * @param  Response $res
     * @return mixed
     */
    public function index(Request $req, Response $res)
    {
        $authUser = auth()->user();
        if (!$authUser) {
            return $res->redirect('/login');
        }

        $perPage = (int) $req->query('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100], true) ? $perPage : 20;

        $query = User::where('tenant_id', $authUser->tenant_id)
            ->orderBy('id', 'desc');

        // Pesquisa rápida por nome ou email
        if ($search = trim((string) $req->query('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($status = $req->query('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('status', $status);
            }
        }

        $employees = $query->paginate($perPage);

        // Pedido AJAX → devolve apenas o HTML parcial da tabela + paginação
        if ($req->getHeader('X-Requested-With') === 'XMLHttpRequest') {
            return view('partials.employees-table', [
                'employees' => $employees,
            ]);
        }

        return view('admin.employees', [
            'employees' => $employees,
        ]);
    }

    /**
     * Cria um novo funcionário.
     *
     * @param  EmployeeRequest $req
     * @param  Response        $res
     * @return mixed Resposta JSON (201 em sucesso).
     */
    public function store(EmployeeRequest $req, Response $res)
    {
        $data = $req->validated();
        
        $employee = User::create([
            'tenant_id' => (int) auth()->user()->tenant_id,
            'name'      => trim((string) $data['name']),
            'email'     => strtolower(trim((string) $data['email'])),
            'phone'     => !empty($data['phone']) ? trim((string) $data['phone']) : null,
            'password'  => password_hash($data['password'], PASSWORD_BCRYPT),
            'status'    => $data['status'] ?? 'active',
            'is_admin'  => 0,
        ]);

        return $res->json([
            'success' => true,
            'message' => 'Funcionário criado com sucesso.',
            'data'    => $employee,
        ], 201);
    }

    /**
     * Actualiza um funcionário existente.
     *
     * @param  EditEmployeeRequest $req
     * @param  Response            $res
     * @return mixed Resposta JSON (404 se não existir).
     */
    public function update(EditEmployeeRequest $req, Response $res)
    {
        $data     = $req->validated();
        $employee = User::find((int) ($data['id'] ?? 0));

        if (!$employee) {
            return $res->json([
                'success' => false,
                'message' => 'Funcionário não encontrado.',
            ], 404);
        }

        $updateData = [
            'name'   => trim((string) $data['name']),
            'email'  => strtolower(trim((string) $data['email'])),
            'phone'  => !empty($data['phone']) ? trim((string) $data['phone']) : null,
            'status' => $data['status'] ?? 'active',
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $employee->update($updateData);

        return $res->json([
            'success' => true,
            'message' => 'Funcionário actualizado com sucesso.',
        ]);
    }
}