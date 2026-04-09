<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // GET ALL PRODUCTS
    public function index()
    {
        $products = Product::with(['category', 'crosses', 'matchCars'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    // SEARCH
    public function search(Request $request)
    {
        $q = $request->q;
        $mode = $request->mode;

        if ($mode === 'product') {
            $products = Product::where('nama_produk', 'like', "%$q%")
                ->orWhere('brand_produk', 'like', "%$q%")
                ->orWhereHas('crosses', function ($query) use ($q) {
                    $query->where('oem_number', 'like', "%$q%");
                })
                ->with(['category', 'crosses', 'matchCars'])
                ->get();
        } else {
            $products = Product::whereHas('matchCars', function ($query) use ($q) {
                $query->where('car_brand', 'like', "%$q%")
                      ->orWhere('car_type', 'like', "%$q%");
            })
            ->with(['category', 'crosses', 'matchCars'])
            ->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }
}