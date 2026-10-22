<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Requests\EditProductRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Slenix\Http\Request;
use Slenix\Http\Response;

/**
 * Controlador de gestão do catálogo de produtos (listagem, criação e actualização).
 *
 * A listagem suporta paginação, pesquisa e filtros via query string.
 * As operações de criação/actualização devolvem JSON para serem consumidas por AJAX.
 */
class ProductController
{
    /**
     * Mostra a página de listagem de produtos.
     *
     * Aceita os seguintes parâmetros de query:
     * page        (int)    Página actual
     * per_page    (int)    Registos por página (10, 20, 50, 100)
     * search      (string) Pesquisa rápida (nome, código ou código de barras)
     * category_id (int)    Filtro por categoria
     * status      (string) Filtro por estado (active|inactive)
     * stock_level (string) Filtro de stock (low|zero)
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

        $perPage = (int) $req->query('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100], true) ? $perPage : 20;

        $query = Product::with('category')
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('id', 'desc');

        // Pesquisa rápida
        if ($search = trim((string) $req->query('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Filtro por categoria
        if ($categoryId = (int) $req->query('category_id', 0)) {
            $query->where('category_id', $categoryId);
        }

        // Filtro por estado
        if ($status = $req->query('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('status', $status);
            }
        }

        // Filtro de nível de stock
        if ($stockLevel = $req->query('stock_level')) {
            if ($stockLevel === 'low') {
                $query->whereColumn('stock', '<=', 'min_stock');
            } elseif ($stockLevel === 'zero') {
                $query->where('stock', 0);
            }
        }

        $products = $query->paginate($perPage);

        // Pedido AJAX → devolve apenas o HTML parcial da tabela + paginação
        if ($req->getHeader('X-Requested-With') === 'XMLHttpRequest') {
            return view('partials.products-table', [
                'products' => $products,
            ]);
        }

        return view('admin.product', [
            'products'   => $products,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Cria um novo produto.
     *
     * A validação é feita pelo ProductRequest. Em caso de falha o framework
     * deve responder com HTTP 422 e o formato:
     * {"message": "...", "errors": {"campo": ["mensagem"]}}.
     *
     * @param  ProductRequest $req
     * @param  Response       $res
     * @return mixed Resposta JSON (201 em sucesso).
     */
    public function store(ProductRequest $req, Response $res)
    {
        $product = Product::create($this->normalize($req->validated()));

        return $res->json([
            'success' => true,
            'message' => 'Produto criado com sucesso.',
            'data'    => $product,
        ], 201);
    }

    /**
     * Actualiza um produto existente.
     *
     * @param  EditProductRequest $req
     * @param  Response           $res
     * @param  int                $id ID do produto extraído da rota.
     * @return mixed Resposta JSON (404 se o produto não existir).
     */
    /**
     * Actualiza um produto existente.
     *
     * @param  EditProductRequest $req
     * @param  Response           $res
     * @return mixed Resposta JSON (404 se o produto não existir).
     */
    public function update(EditProductRequest $req, Response $res)
    {
        $data    = $req->validated();
        $product = Product::find((int) ($data['id'] ?? 0));

        if (!$product) {
            return $res->json([
                'success' => false,
                'message' => 'Produto não encontrado.',
            ], 404);
        }

        $product->update($this->normalize($data));

        return $res->json([
            'success' => true,
            'message' => 'Produto actualizado com sucesso.',
        ]);
    }

    /**
     * Normaliza e sanitiza os dados validados antes de persistir.
     *
     * @param  array<string, mixed> $in Dados validados.
     * @return array<string, mixed> Atributos limpos prontos para o modelo.
     */
    private function normalize(array $in): array
    {
        $barcode     = trim((string) ($in['barcode'] ?? ''));
        $description = trim((string) ($in['description'] ?? ''));
        $user        = auth()->user();

        return [
            'tenant_id'   => $user ? (int) $user->tenant_id : 0,
            'name'        => trim((string) ($in['name'] ?? '')),
            'code'        => strtoupper(trim((string) ($in['code'] ?? ''))),
            'barcode'     => $barcode !== '' ? $barcode : null,
            'category_id' => (int) ($in['category_id'] ?? 0),
            'cost_price'  => (float) ($in['cost_price'] ?? 0),
            'sale_price'  => (float) ($in['sale_price'] ?? 0),
            'stock'       => (int) ($in['stock'] ?? 0),
            'min_stock'   => (int) ($in['min_stock'] ?? 0),
            'status'      => $in['status'] ?? 'active',
            'description' => $description !== '' ? $description : null,
        ];
    }
}