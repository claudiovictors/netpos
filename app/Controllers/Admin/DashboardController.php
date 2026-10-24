<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Slenix\Http\Request;
use Slenix\Http\Response;

class DashboardController
{
    /**
     * Página inicial do painel administrativo com contadores do tenant.
     *
     * @param  Request  $req
     * @param  Response $res
     * @return mixed
     */
    public function index(Request $req, Response $res)
    {
        $user = auth()->user();
        if (!$user) {
            return $res->redirect('/login');
        }

        $tenantId = $user->tenant_id;

        // Produtos activos do tenant
        $totalProduct = Product::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->count();

        // Stock crítico: stock actual <= stock mínimo (Contador)
        $totalProductCrit = Product::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->whereColumn('stock', '<=', 'min_stock')
            ->count();

        // Lista de produtos em stock crítico para a tabela da dashboard (ex: primeiros 5)
        $criticalProducts = Product::with('category')
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->whereColumn('stock', '<=', 'min_stock')
            ->limit(5)
            ->get();

        // Clientes do tenant
        $totalCustomers = Customer::where('tenant_id', $tenantId)->count();

        // Categorias
        $totalCategory = Category::count();

        return view('admin.dashbord', compact(
            'totalProduct',
            'totalProductCrit',
            'totalCustomers',
            'totalCategory',
            'criticalProducts' // <-- Passamos a variável para a view
        ));
    }
}