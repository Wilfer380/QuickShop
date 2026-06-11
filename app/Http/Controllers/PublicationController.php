<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\StoreVehiclePublicationRequest;
use App\Http\Requests\Inventory\UpdateVehiclePublicationRequest;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\VehiclePublication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PublicationController extends Controller
{
    public function index()
    {
        $this->ensureInventoryAccess();
        $userId = Auth::user()->id;

        $inventoryItems = VehiclePublication::where('user_id', $userId)
            ->with(['productImages', 'category.parent'])
            ->get();
        $categories = Category::with('parent')->orderBy('parent_id')->orderBy('name')->get();

        return view('App.modules.Inventory.products.index', compact('inventoryItems', 'categories'));
    }

    public function create()
    {
        $this->ensureInventoryAccess();
        $categories = Category::with('parent')->orderBy('parent_id')->orderBy('name')->get();

        return view('App.modules.Inventory.products.create', compact('categories'));
    }

    public function store(StoreVehiclePublicationRequest $request)
    {
        $this->ensureInventoryAccess();

        $product = VehiclePublication::create([
            'user_id' => auth()->id(),
            'category_id' => $request->validated('category_id'),
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'price' => $request->validated('price'),
            'stock' => $request->validated('stock'),
        ]);

        $this->storePublicationImage($product, $request->file('image'));

        return redirect()->route('vehicle-publications.index');
    }

    public function edit(string $id)
    {
        $this->ensureInventoryAccess();
        $categories = Category::with('parent')->orderBy('parent_id')->orderBy('name')->get();
        $product = VehiclePublication::findOrFail($id);
        $image = ProductImage::where('product_id', $product->id)->get();

        return view('App.modules.Inventory.products.edit', compact('categories', 'product', 'image'));
    }

    public function update(UpdateVehiclePublicationRequest $request, string $id)
    {
        $this->ensureInventoryAccess();

        $product = VehiclePublication::with('productImages')->findOrFail($id);

        $product->name = $request->validated('name');
        $product->category_id = $request->validated('category_id');
        $product->description = $request->validated('description');
        $product->price = $request->validated('price');
        $product->stock = $request->validated('stock');
        $product->save();

        if ($request->hasFile('image')) {
            $this->replacePublicationImage($product, $request->file('image'));
        }

        return redirect()->route('vehicle-publications.index');
    }

    public function destroy($id)
    {
        $this->ensureInventoryAccess();

        $product = VehiclePublication::with('productImages')->findOrFail($id);

        foreach ($product->productImages as $image) {
            $imagePath = storage_path('app/public/' . $image->image_path);

            if (file_exists($imagePath)) {
                File::delete($imagePath);
            }

            $image->delete();
        }

        $product->delete();

        return redirect()->route('vehicle-publications.index')->with('success', 'Publicacion eliminada correctamente.');
    }

    private function storePublicationImage(VehiclePublication $product, $uploadedFile): void
    {
        if (! $uploadedFile) {
            return;
        }

        $imagePath = $uploadedFile->store('vehicle_inventory', 'public');

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $imagePath,
        ]);
    }

    private function replacePublicationImage(VehiclePublication $product, $uploadedFile): void
    {
        foreach ($product->productImages as $image) {
            $imagePath = storage_path('app/public/' . $image->image_path);

            if (file_exists($imagePath)) {
                File::delete($imagePath);
            }

            $image->delete();
        }

        $this->storePublicationImage($product, $uploadedFile);
    }

    private function ensureInventoryAccess(): void
    {
        if (! Auth::user()) {
            abort(403, 'No tienes permiso para acceder a esta seccion.');
        }
    }
}
