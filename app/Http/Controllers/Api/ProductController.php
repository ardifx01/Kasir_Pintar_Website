<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            Gate::authorize("viewAny", Product::class);
            $user = Auth::user();
            $storeId = $user->stores()->first()?->id ?? null;
            $products = $storeId
                ? Product::where("store_id", $storeId)
                    ->with("categoryProduct", "store")
                    ->get()
                : Product::all();
            return response()->json($products, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function indexByStore(Request $request, $storeId)
    {
        try {
            Gate::authorize("viewAny", Product::class);
            if (
                !Auth::user()->isAdmin() &&
                !Auth::user()->ownsStore($storeId)
            ) {
                throw new AuthorizationException("Unauthorized");
            }
            $products = Product::where("store_id", $storeId)
                ->with("categoryProduct", "store")
                ->get();
            return response()->json($products, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function store(Request $request)
    {
        try {
            Gate::authorize("create", Product::class);
            $rules = [
                "name_product" => ["required", "string", "max:255"],
                "code_product" => ["required", "string", "unique:products"],
                "selling_price" => ["required", "numeric", "min:0"],
                "purchase_price" => ["required", "numeric", "min:0"],
                "stock" => ["required", "integer", "min:0"],
                "unit" => ["required", "string"],
                "image" => [
                    "nullable",
                    "image",
                    "mimes:jpeg,png,jpg,gif",
                    "max:2048",
                ],
                "store_id" => ["required", "exists:stores,id"],
                "category_product_id" => [
                    "required",
                    "exists:category_products,id",
                ],
            ];
            $messages = [
                "code_product.unique" => "Kode produk sudah ada.",
                "store_id.exists" => "ID toko tidak valid.",
                "category_product_id.exists" =>
                    "ID kategori produk tidak valid.",
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }
            $validatedData = $request->all();
            if ($request->hasFile("image")) {
                $imagePath = $request
                    ->file("image")
                    ->store("product_images", "public");
                $validatedData["url_image"] = $imagePath;
            }
            $product = Product::create($validatedData);
            return response()->json($product, 201);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function show(Product $product)
    {
        try {
            Gate::authorize("view", $product);
            return response()->json(
                $product->load("categoryProduct", "store"),
                200
            );
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            Gate::authorize("update", $product);
            $rules = [
                "name_product" => ["nullable", "string", "max:255"],
                "code_product" => [
                    "nullable",
                    "string",
                    "unique:products,code_product," . $product->id,
                ],
                "selling_price" => ["nullable", "numeric", "min:0"],
                "purchase_price" => ["nullable", "numeric", "min:0"],
                "stock" => ["nullable", "integer", "min:0"],
                "unit" => ["nullable", "string"],
                "store_id" => ["nullable", "exists:stores,id"],
                "category_product_id" => [
                    "nullable",
                    "exists:category_products,id",
                ],
            ];
            $messages = [
                "code_product.unique" => "Kode produk sudah ada.",
                "store_id.exists" => "ID toko tidak valid.",
                "category_product_id.exists" =>
                    "ID kategori produk tidak valid.",
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }
            $product->update($request->all());
            return response()->json($product, 200);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function updateImage(Request $request, Product $product)
    {
        try {
            Gate::authorize("update", $product);
            $request->validate([
                "image" => [
                    "required",
                    "image",
                    "mimes:jpeg,png,jpg,gif",
                    "max:2048",
                ],
            ]);
            if ($product->url_image) {
                Storage::disk("public")->delete($product->url_image);
            }
            $imagePath = $request
                ->file("image")
                ->store("product_images", "public");
            $product->url_image = $imagePath;
            $product->save();
            return response()->json($product, 200);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function destroy(Product $product)
    {
        try {
            Gate::authorize("delete", $product);
            $product->delete();
            return response()->json(
                ["message" => "Product deleted successfully"],
                200
            );
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function categoryProducts(Request $request)
    {
        try {
            $categoryProducts = CategoryProduct::all();
            return response()->json($categoryProducts, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }
}
