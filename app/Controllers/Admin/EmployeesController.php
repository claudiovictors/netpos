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
 *
 * Listagem com paginação, pesquisa e filtro de estado;
 * criação, actualização e eliminação via JSON (AJAX).
 */
class EmployeesController
{
    /**
     * Mostra a listagem de funcionários.
     *
     * Query: page, per_page, search (nome|email|telefone), status (active|inactive).
     * Pedidos AJAX devolvem apenas o partial da tabela.
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

        if ($search = trim((string) $req->query('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $req->query('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('status', $status);
            }
        }

        $employees = $query->paginate($perPage);

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
            'name' => trim((string) ($data['name'] ?? '')),
            'email' => strtolower(trim((string) ($data['email'] ?? ''))),
            'phone' => !empty($data['phone']) ? trim((string) $data['phone']) : null,
            'password' => password_hash((string) ($data['password'] ?? ''), PASSWORD_BCRYPT),
            'status' => $data['status'] ?? 'active',
            'is_admin' => 0,
        ]);

        return $res->json([
            'success' => true,
            'message' => 'Funcionário criado com sucesso.',
            'data' => $employee,
        ], 201);
    }

    /**
     * Actualiza um funcionário existente (apenas do tenant actual).
     *
     * @param  EditEmployeeRequest $req
     * @param  Response            $res
     * @return mixed Resposta JSON (404 se não existir).
     */
    public function update(EditEmployeeRequest $req, Response $res)
    {
        $data = $req->validated();
        $employee = $this->findOwnedEmployee((int) ($data['id'] ?? 0));

        if (!$employee) {
            return $res->json([
                'success' => false,
                'message' => 'Funcionário não encontrado.',
            ], 404);
        }

        $updateData = [
            'name' => trim((string) ($data['name'] ?? '')),
            'email' => strtolower(trim((string) ($data['email'] ?? ''))),
            'phone' => !empty($data['phone']) ? trim((string) $data['phone']) : null,
            'status' => $data['status'] ?? 'active',
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = password_hash((string) $data['password'], PASSWORD_BCRYPT);
        }

        $employee->update($updateData);

        return $res->json([
            'success' => true,
            'message' => 'Funcionário actualizado com sucesso.',
        ]);
    }

    /**
     * Elimina um funcionário (apenas do tenant actual).
     *
     * Não permite eliminar a própria conta autenticada.
     *
     * @param  Request  $req
     * @param  Response $res
     * @param  array    $param Parâmetros de rota (ex.: ['id' => '12']).
     * @return mixed Resposta JSON.
     */
    public function delete(Request $req, Response $res, array $param)
    {
        $authUser = auth()->user();
        $id = (int) ($param['id'] ?? 0);

        if ($authUser && $id === (int) $authUser->id) {
            return $res->json([
                'success' => false,
                'message' => 'Não pode eliminar a sua própria conta.',
            ], 403);
        }

        $employee = $this->findOwnedEmployee($id);

        if (!$employee) {
            return $res->json([
                'success' => false,
                'message' => 'Funcionário não encontrado.',
            ], 404);
        }

        $employee->delete();

        return $res->json([
            'success' => true,
            'message' => 'Funcionário eliminado com sucesso.',
        ]);
    }

    /**
     * Localiza um funcionário pelo ID restringindo ao tenant actual.
     *
     * @param  int $id
     * @return User|null
     */
    private function findOwnedEmployee(int $id): ?User
    {
        if ($id <= 0) {
            return null;
        }

        $authUser = auth()->user();
        if (!$authUser) {
            return null;
        }

        return User::where('id', $id)
            ->where('tenant_id', $authUser->tenant_id)
            ->first();
    }
}