<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->id;

        $products_user = Product::where('user_id', $userId)->with('productImages')->get();
        $categories = Category::all();

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

        return view('App.modules.Sellers.products.index', compact('products_user', 'categories', 'cartProducts', 'cart_count', 'precioTotal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

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

        return view('App.modules.Sellers.products.create', compact('categories', 'cartProducts', 'cart_count', 'precioTotal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $product = Product::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imagePath,
            ]);
        }

        return redirect()->route('seller.products.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $categories = Category::all();
        $product = Product::findOrFail($id);

        if ($product->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este producto.');
        }

        $image = ProductImage::where('product_id', $product->id)->get();

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
        foreach ($cartProducts as $cartProduct) {
            $precioTotal += $cartProduct->price;
        }

        return view('App.modules.Sellers.products.edit', compact('categories', 'product', 'image', 'cartProducts', 'cart_count', 'precioTotal'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $product = Product::with('productImages')->findOrFail($id);

        if ($product->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este producto.');
        }

        $product->name = $validated['name'];
        $product->category_id = $validated['category_id'];
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->save();

        if ($request->hasFile('image')) {
            foreach ($product->productImages as $image) {
                $imagePath = storage_path('app/public/' . $image->image_path);

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                $image->delete();
            }

            $imagePath = $request->file('image')->store('product_images', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imagePath,
            ]);
        }

        return redirect()->route('seller.products.index');
    }

    public function destroy($id)
    {
        $product = Product::with('productImages')->findOrFail($id);

        if ($product->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este producto.');
        }

        foreach ($product->productImages as $image) {
            $imagePath = storage_path('app/public/' . $image->image_path);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $image->delete();
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Producto eliminado correctamente.');
    }
}
