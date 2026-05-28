<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class shoppingCartController extends Controller
{
    public function addProduct(string $id)
    {
        $ids = Session::get('List_products_shop', []);

        if (! in_array($id, $ids)) {
            $ids[] = $id;
            Session::put('List_products_shop', $ids);
        }

        return redirect()->route('buyers.index');
    }

    /**
     * comprar los productos.
     */
    public function index()
    {
        $listProductsShop = Session::get('List_products_shop', []);

        if (count($listProductsShop) === 0) {
            return redirect()->route('buyers.index');
        }

        $cartProducts = Product::whereIn('id', $listProductsShop)->get();

        if ($cartProducts->count() !== count($listProductsShop)) {
            return redirect()->route('buyers.index');
        }

        $precioTotal = $cartProducts->sum('price');

        $user = User::findOrFail(auth()->id());

        if ($user->money <= 0 || $user->money < $precioTotal) {
            return redirect()->route('buyers.index');
        }

        DB::transaction(function () use ($listProductsShop, $precioTotal) {
            $buyer = User::query()->lockForUpdate()->findOrFail(auth()->id());
            $products = Product::query()
                ->whereIn('id', $listProductsShop)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($listProductsShop)) {
                throw new \RuntimeException('Some products are no longer available.');
            }

            foreach ($listProductsShop as $productId) {
                $product = $products->get((int) $productId);

                if (! $product || $product->stock < 1) {
                    throw new \RuntimeException('Product is out of stock.');
                }
            }

            if ($buyer->money < $precioTotal) {
                throw new \RuntimeException('Insufficient funds.');
            }

            $buyer->decrement('money', $precioTotal);

            $order = Order::create([
                'user_id' => $buyer->id,
                'status' => 'completed',
                'total' => $precioTotal,
            ]);

            foreach ($listProductsShop as $productId) {
                $product = $products->get((int) $productId);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $product->price,
                ]);

                $product->decrement('stock', 1);

                $seller = User::query()->lockForUpdate()->findOrFail($product->user_id);
                $seller->increment('money', $product->price);
            }
        });

        Session::put('List_products_shop', []);

        return redirect()->route('buyers.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $ids = Session::get('List_products_shop', []);

        if (($key = array_search($id, $ids)) !== false) {
            unset($ids[$key]);
            $ids = array_values($ids);
            Session::put('List_products_shop', $ids);
        }

        return redirect()->route('buyers.index');
    }
}
