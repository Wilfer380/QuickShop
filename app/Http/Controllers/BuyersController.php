<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class BuyersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()
            ->withCount('products')
            ->with(['products' => function ($query) {
                $query->with('productImages')->orderBy('id');
            }])
            ->orderBy('name')
            ->get();

        $selectedCategoryId = request()->query('c');
        $search = trim((string) request()->query('q', ''));
        $sort = request()->query('sort', 'newest');

        $productsQuery = Product::query()
            ->with(['productImages', 'category', 'user'])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $productQuery) use ($search) {
                    $productQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            });

        if ($selectedCategoryId) {
            $productsQuery->where('category_id', $selectedCategoryId);
        }

        match ($sort) {
            'price_asc' => $productsQuery->orderBy('price'),
            'price_desc' => $productsQuery->orderByDesc('price'),
            'stock_desc' => $productsQuery->orderByDesc('stock'),
            default => $productsQuery->latest(),
        };

        $products = $productsQuery->get();

        $listProductsShop = Session::get('List_products_shop', []);
        $cart_count = count($listProductsShop);

        if ($cart_count > 0) {
            $cartProducts = Product::whereIn('id', $listProductsShop)
                ->with('productImages')
                ->get();
        } else {
            $cartProducts = [];
            $cart_count = 0;
        }

        $precioTotal = 0;
        foreach ($cartProducts as $product) {
            $precioTotal += $product->price;
        }

        return view('App.modules.Buyers.index', compact(
            'products',
            'categories',
            'cart_count',
            'cartProducts',
            'precioTotal',
            'selectedCategoryId',
            'search',
            'sort'
        ));
    }

    public function getPurchaseHistory()
    {
        $user = User::with(['orders.orderItems.product.productImages'])->findOrFail(auth()->id());

        $purchaseHistory = $user->orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'status' => $order->status,
                'total' => $order->total,
                'created_at' => $order->created_at,
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'images' => $item->product->productImages->map(function ($image) {
                            return $image->image_path;
                        }),
                    ];
                }),
            ];
        });

        $listProductsShop = Session::get('List_products_shop', []);
        $cart_count = count($listProductsShop);

        if ($cart_count > 0) {
            $cartProducts = Product::whereIn('id', $listProductsShop)
                ->with('productImages')
                ->get();
        } else {
            $cartProducts = [];
            $cart_count = 0;
        }

        $precioTotal = 0;
        foreach ($cartProducts as $product) {
            $precioTotal += $product->price;
        }

        return view('App.modules.Buyers.cartShop.index', compact('user', 'purchaseHistory', 'cartProducts', 'precioTotal', 'cart_count'));
    }
}
