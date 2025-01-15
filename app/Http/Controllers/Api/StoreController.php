<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user->isAdmin()) {
                Gate::authorize("viewAny", Store::class);
                $stores = Store::all();
            } elseif ($user->isOwner()) {
                Gate::authorize("viewAny", Store::class); //This is still needed for authorization.
                $stores = $user->stores()->get(); // Fetch only stores owned by the user
            } elseif ($user->isStaff()) {
                Gate::authorize("viewAny", Store::class);
                $staff = $user->staff()->first();
                if ($staff) {
                    $stores = Store::where("id", $staff->store_id)->get();
                } else {
                    $stores = collect([]); // Return empty collection if staff has no store
                }
            } else {
                $stores = collect([]); // Return empty collection for unauthorized users
            }

            return response()->json($stores, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }

    public function show(Store $store)
    {
        try {
            Gate::authorize("view", $store);
            return response()->json($store, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }

    public function store(Request $request)
    {
        try {
            Gate::authorize("create", Store::class);
            $validatedData = $request->validate([
                "name" => ["required", "string", "max:255"],
                "number_phone" => ["required", "string"],
                "postal_code" => ["required", "string"],
                "address" => ["required", "string"],
                "image" => [
                    "nullable",
                    "image",
                    "mimes:jpeg,png,jpg,gif",
                    "max:2048",
                ],
            ]);

            $user = Auth::user();

            if ($request->hasFile("image")) {
                $imagePath = $request
                    ->file("image")
                    ->store("store_images", "public");
                $validatedData["url_image"] = $imagePath;
            }

            $store = $user->stores()->create($validatedData);

            return response()->json($store, 201);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }

    public function update(Request $request, Store $store)
    {
        try {
            Gate::authorize("update", $store);

            $validator = Validator::make($request->all(), [
                "name" => ["nullable", "string", "max:255"],
                "number_phone" => ["nullable", "string"],
                "postal_code" => ["nullable", "string"],
                "address" => ["nullable", "string"],
            ]);

            if ($validator->fails()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }

            $store->update($request->all()); //Use request->validated() for cleaner code

            return response()->json($store, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }

    public function updateImage(Request $request, Store $store)
    {
        try {
            Gate::authorize("update", $store);

            $validator = Validator::make($request->all(), [
                "image" => [
                    "required",
                    "image",
                    "mimes:jpeg,png,jpg,gif",
                    "max:2048",
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }

            if ($store->url_image) {
                Storage::disk("public")->delete($store->url_image);
            }

            $imagePath = $request
                ->file("image")
                ->store("store_images", "public");
            $store->url_image = $imagePath;
            $store->save();

            return response()->json($store, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }

    public function destroy(Store $store)
    {
        try {
            Gate::authorize("delete", $store);
            $store->delete();
            return response()->json(["message" => "Store deleted"], 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }
}
