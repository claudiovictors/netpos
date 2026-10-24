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
    public function index(Request $req, Response $res)
    {
        $totalProduct = Product::where('status', 'active')->count();
        $totalProductCrit = Product::select('min_stock')->count();
        $totalCustomers = Customer::count();
        $totalCategory = Category::count();

        return view('admin.dashbord', compact(
            'totalProduct',
            'totalProductCrit',
            'totalCustomers',
            'totalCategory',
        ));
    }
}