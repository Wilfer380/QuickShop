<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class BuyersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()->orderBy('name')->get();
        $selectedCategoryId = request()->query('c');

        $productsQuery = Product::query()
            ->with(['productImages', 'category', 'user'])
            ->latest();

        if ($selectedCategoryId) {
            $productsQuery->where('category_id', $selectedCategoryId);
        }

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
            'selectedCategoryId'
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
