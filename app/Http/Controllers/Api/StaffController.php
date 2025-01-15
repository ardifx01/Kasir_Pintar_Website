<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index()
    {
        try {
            Gate::authorize("viewAny", Staff::class);
            $user = Auth::user();
            $storeId = $user->stores()->first()?->id ?? null;
            $staffs = $storeId
                ? Staff::where("store_id", $storeId)
                    ->with("user", "store")
                    ->get()
                : Staff::all();
            return response()->json($staffs, 200);
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
            Gate::authorize("viewAny", Staff::class); //Check authorization at the beginning
            if (
                !Auth::user()->isAdmin() &&
                !Auth::user()->ownsStore($storeId)
            ) {
                throw new AuthorizationException("Unauthorized");
            }
            $staffs = Staff::where("store_id", $storeId)
                ->with("user", "store")
                ->get();
            return response()->json($staffs, 200);
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
            Gate::authorize("create", Staff::class);
            $rules = [
                "name" => "required|string|max:255",
                "email" => "required|email|unique:users",
                "password" => "required|min:6",
                "number_phone" => "required|string",
                "store_id" => "required|exists:stores,id",
                "role" => "required|string",
            ];
            $messages = [
                "email.unique" => "Email already exists.",
                "store_id.exists" => "Invalid store ID.",
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "number_phone" => $request->number_phone,
                "role" => "staff",
            ]);
            $staff = Staff::create([
                "store_id" => $request->store_id,
                "user_id" => $user->id,
                "role" => $request->role,
            ]);
            return response()->json($staff, 201);
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

    public function show(Staff $staff)
    {
        try {
            Gate::authorize("view", $staff);
            return response()->json($staff->load("user", "store"), 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function update(Request $request, Staff $staff)
    {
        try {
            Gate::authorize("update", $staff);
            $rules = [
                "name" => "nullable|string|max:255",
                "email" =>
                    "nullable|email|unique:users,email," . $staff->user->id,
                "password" => "nullable|min:6",
                "number_phone" => "nullable|string",
                "store_id" => "nullable|exists:stores,id",
                "role" => "nullable|string",
            ];
            $messages = [
                "email.unique" => "Email already exists.",
                "store_id.exists" => "Invalid store ID.",
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }
            $updateData = $request->all();
            if (isset($updateData["password"])) {
                $updateData["password"] = Hash::make($updateData["password"]);
            }
            $staff->user()->update($updateData);
            $staff->update([
                "role" => $request->role,
                "store_id" => $request->store_id,
            ]);
            return response()->json($staff, 200);
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

    public function destroy(Staff $staff)
    {
        try {
            Gate::authorize("delete", $staff);
            DB::transaction(function () use ($staff) {
                $staff->user()->delete();
                $staff->delete();
            });
            return response()->json(
                ["message" => "Staff and user deleted successfully"],
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
