<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Store;
use App\Models\CategoryProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    private function getAccessibleStores(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return Store::all();
        } elseif ($user->isStaff()) {
            $staff = $user->staff()->first();
            return $staff ? collect([$staff->store]) : collect([]);
        } elseif ($user->isOwner()) {
            return $user->stores()->get();
        }
        return collect([]);
    }

    public function index()
    {
        $this->authorize("viewAny", Product::class);

        $user = Auth::user();
        $products = null;
        $stores = $this->getAccessibleStores();
        $role = $user->role;

        if ($user->isAdmin()) {
            $products = Product::with("store", "categoryProduct")->get();
        } elseif ($user->isOwner()) {
            $firstStore = $user->stores()->first();
            $products = $firstStore
                ? $firstStore
                    ->products()
                    ->with("store", "categoryProduct")
                    ->get()
                : collect([]);
        } elseif ($user->isStaff()) {
            $staff = $user->staff()->first();
            $products = $staff
                ? $staff->store
                    ->products()
                    ->with("store", "categoryProduct")
                    ->get()
                : collect([]);
        }

        return view("products.index", compact("products", "stores", "role"));
    }

    public function show(string $store_id)
    {
        $store = Store::find($store_id);
        if (!$store) {
            abort(404, "Store not found");
        }

        $user = Auth::user();
        if (
            !$user->isAdmin() &&
            !$store->owner_id == $user->id &&
            !$user->staff()->where("store_id", $store_id)->exists()
        ) {
            abort(403, "Unauthorized");
        }

        $products = $store->products()->with("categoryProduct")->get();
        $stores = $this->getAccessibleStores(); // Get accessible stores for the view
        $role = $user->role; // Get user role for the view
        $categoryProducts = \App\Models\CategoryProduct::all(); // Get all category products for the view

        return view(
            "products.show",
            compact("products", "store", "stores", "role", "categoryProducts")
        );
    }

    public function create()
    {
        $this->authorize("create", Product::class);
        $stores = $this->getAccessibleStores();
        $categoryProducts = CategoryProduct::all(); // Added to get category products
        $role = Auth::user()->role;
        return view(
            "products.create",
            compact("stores", "categoryProducts", "role")
        );
    }

    public function store(ProductRequest $request)
    {
        $this->authorize("create", Product::class);
        try {
            $validatedData = $request->validated();

            if ($request->hasFile("url_image")) {
                $image = $request->file("url_image");
                $imageName = time() . "_" . $image->getClientOriginalName();
                $image->storeAs("product_images/", $imageName, "public"); // Store in the public disk
                $validatedData["url_image"] = $imageName;
            }

            Product::create($validatedData);
            return redirect()
                ->route("products.index")
                ->with("success", "Produk berhasil ditambahkan.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $this->authorize("update", $product);
        $stores = $this->getAccessibleStores()->pluck("name", "id")->toArray();
        $categoryProducts = CategoryProduct::all()
            ->pluck("name", "id")
            ->toArray();
        $role = Auth::user()->role;
        return view(
            "products.edit",
            compact("product", "stores", "categoryProducts", "role")
        );
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize("update", $product);
        try {
            $validatedData = $request->validated();

            // Handle image updates
            if ($request->hasFile("url_image")) {
                // Delete old image if exists
                if ($product->image) {
                    Storage::disk("public")->delete(
                        "products/" . $product->image
                    );
                }
                $image = $request->file("url_image");
                $imageName = time() . "_" . $image->getClientOriginalName();
                $image->storeAs("public/products", $imageName);
                $validatedData["image"] = $imageName;
            }

            $product->update($validatedData);
            return redirect()
                ->route("products.index")
                ->with("success", "Produk berhasil diubah.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $this->authorize("delete", $product);
        try {
            // Delete associated image
            if ($product->image) {
                Storage::disk("public")->delete("products/" . $product->image);
            }
            $product->delete();
            return redirect()
                ->route("products.index")
                ->with("success", "Produk berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }
}
