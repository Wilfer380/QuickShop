<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class moneyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listProductsShop = Session::get('List_products_shop', []);
        $cart_count = count($listProductsShop);

        if ($cart_count > 0) {
            $cartProducts = Product::whereIn('id', $listProductsShop)
                ->with('productImages')
                ->get();
        } else {
            $cartProducts = [];
        }

        $precioTotal = 0;
        foreach ($cartProducts as $product) {
            $precioTotal += $product->price;
        }

        return view('App.modules.money.index', compact('cartProducts', 'cart_count', 'precioTotal'));
    }

    public function deposit_money(Request $request)
    {
        $validated = $request->validate([
            'cantidad' => ['required', 'numeric', 'min:0.01'],
        ]);

        $user = User::findOrFail(auth()->id());
        $user->money = $user->money + $validated['cantidad'];
        $user->save();

        return redirect()->route('buyers.index');
    }

    public function withdraw_money(Request $request)
    {
        $validated = $request->validate([
            'cantidad' => ['required', 'numeric', 'min:0.01'],
        ]);

        $money = $validated['cantidad'];
        $user = User::findOrFail(auth()->id());

        if ($user->money - $money < 0) {
            return redirect()->route('buyers.index');
        }

        $user->money = $user->money - $money;
        $user->save();

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
        //
    }
}
