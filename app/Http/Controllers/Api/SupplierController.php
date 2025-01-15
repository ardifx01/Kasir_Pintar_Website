<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        try {
            Gate::authorize("viewAny", Supplier::class);
            $user = Auth::user();
            $storeId = $user->stores()->first()?->id ?? null;
            $suppliers = $storeId
                ? Supplier::where("store_id", $storeId)->with("store")->get()
                : Supplier::all();
            return response()->json($suppliers, 200);
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
            // Authorization: Only admins can access this endpoint
            if (!Auth::user()->isAdmin()) {
                throw new AuthorizationException("Unauthorized");
            }

            $suppliers = Supplier::where("store_id", $storeId)
                ->with("store")
                ->get();
            return response()->json($suppliers, 200);
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
            Gate::authorize("create", Supplier::class);
            $rules = [
                "name" => ["required", "string", "max:255"],
                "number_phone" => ["required", "string"],
                "address" => ["required", "string"],
                "email" => ["nullable", "email", "unique:suppliers"],
                "store_id" => ["required", "exists:stores,id"],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }
            $supplier = Supplier::create($request->all());
            return response()->json($supplier, 201);
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

    public function show(Supplier $supplier)
    {
        try {
            //Authorize only if user is admin or owns the store the supplier belongs to.
            if (
                !Auth::user()->isAdmin() &&
                !Auth::user()->ownsStore($supplier->store_id)
            ) {
                throw new AuthorizationException("Unauthorized");
            }

            return response()->json($supplier->load("store"), 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function update(Request $request, Supplier $supplier)
    {
        try {
            Gate::authorize("update", $supplier);
            $rules = [
                "name" => ["nullable", "string", "max:255"],
                "number_phone" => ["nullable", "string"],
                "address" => ["nullable", "string"],
                "email" => [
                    "nullable",
                    "email",
                    "unique:suppliers,email," . $supplier->id,
                ],
                "store_id" => ["nullable", "exists:stores,id"],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }
            $supplier->update($request->all());
            return response()->json($supplier, 200);
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

    public function destroy(Supplier $supplier)
    {
        try {
            Gate::authorize("delete", $supplier);
            $supplier->delete();
            return response()->json(
                ["message" => "Supplier deleted successfully"],
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
}
