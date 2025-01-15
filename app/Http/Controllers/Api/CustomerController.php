<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Store;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            Gate::authorize("viewAny", Customer::class);
            $user = Auth::user();
            $storeId = $user->stores()->first()?->id ?? null;
            $customers = $storeId
                ? Customer::where("store_id", $storeId)->with("store")->get()
                : Customer::all();
            return response()->json($customers, 200);
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
            Gate::authorize("create", Customer::class);
            $rules = [
                "name" => ["required", "string", "max:255"],
                "number_phone" => ["required", "string"],
                "address" => ["required", "string"],
                "email" => ["nullable", "email", "unique:customers"],
                "store_id" => ["required", "exists:stores,id"],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }
            $customer = Customer::create($request->all());
            return response()->json($customer, 201);
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

    public function show(Customer $customer)
    {
        try {
            Gate::authorize("view", $customer);
            return response()->json($customer->load("store"), 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            Gate::authorize("update", $customer);
            $rules = [
                "name" => ["nullable", "string", "max:255"],
                "number_phone" => ["nullable", "string"],
                "address" => ["nullable", "string"],
                "email" => [
                    "nullable",
                    "email",
                    "unique:customers,email," . $customer->id,
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
            $customer->update($request->all());
            return response()->json($customer, 200);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "unathorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            Gate::authorize("delete", $customer);
            $customer->delete();
            return response()->json(
                ["message" => "Customer deleted successfully"],
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
