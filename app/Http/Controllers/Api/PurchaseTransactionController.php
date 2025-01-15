<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseTransaction;
use App\Models\PurchaseDetailTransaction;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Payable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class PurchaseTransactionController extends Controller
{
    public function index(Request $request)
    {
        try {
            Gate::authorize("viewAny", PurchaseTransaction::class);
            $user = Auth::user();
            $storeId = $user->stores()->first()?->id ?? null;
            $transactions = $storeId
                ? PurchaseTransaction::where("store_id", $storeId)
                    ->with(
                        "store",
                        "purchaseDetailTransactions.product",
                        "supplier",
                        "payable"
                    )
                    ->get()
                : PurchaseTransaction::all();
            return response()->json($transactions, 200);
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
            Gate::authorize("create", PurchaseTransaction::class);
            $rules = [
                "store_id" => ["required", "exists:stores,id"],
                "total_discount" => ["required", "numeric", "min:0"],
                "is_debt" => ["required", "boolean"],
                "description" => ["nullable", "string"],
                "payment_method" => ["required", "string"],
                "total_amount" => ["required", "numeric", "min:0"],
                "amount_paid" => ["required", "numeric", "min:0"],
                "change_amount" => ["required", "numeric", "min:0"],
                "transaction_status" => ["required", "string"],
                "purchaseDetailTransactions" => ["required", "array"],
                "purchaseDetailTransactions.*.product_id" => [
                    "required",
                    "exists:products,id",
                ],
                "purchaseDetailTransactions.*.quantity" => [
                    "required",
                    "integer",
                    "min:1",
                ],
                "purchaseDetailTransactions.*.item_discount" => [
                    "required",
                    "numeric",
                    "min:0",
                ],
                "purchaseDetailTransactions.*.subtotal" => [
                    "required",
                    "numeric",
                    "min:0",
                ],
                "supplier_id" => ["required", "exists:suppliers,id"],
                "due_date" => ["nullable", "date"],
            ];
            $messages = [
                "store_id.exists" => "Invalid store ID.",
                "purchaseDetailTransactions.required" =>
                    "Purchase details are required.",
                "purchaseDetailTransactions.*.product_id.exists" =>
                    "Invalid product ID.",
                "purchaseDetailTransactions.*.quantity.min" =>
                    "Quantity must be at least 1.",
                "supplier_id.exists" => "Invalid supplier ID.",
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }

            $transactionData = $request->only(
                "store_id",
                "total_discount",
                "is_debt",
                "description",
                "payment_method",
                "total_amount",
                "amount_paid",
                "change_amount",
                "transaction_status"
            );

            DB::transaction(function () use ($transactionData, $request) {
                $transaction = PurchaseTransaction::create($transactionData); // Create transaction first

                foreach ($request->purchaseDetailTransactions as $detail) {
                    PurchaseDetailTransaction::create([
                        "transaction_id" => $transaction->id,
                        "product_id" => $detail["product_id"],
                        "quantity" => $detail["quantity"],
                        "item_discount" => $detail["item_discount"],
                        "subtotal" => $detail["subtotal"],
                    ]);
                    $product = Product::find($detail["product_id"]);
                    $product->stock += $detail["quantity"];
                    $product->save();
                }
                if ($request->filled("supplier_id") && $request->is_debt) {
                    Payable::create([
                        "transaction_id" => $transaction->id,
                        "supplier_id" => $request->supplier_id,
                        "amount_due" =>
                            $request->total_amount - $request->amount_paid,
                        "payment_status" => "unpaid",
                        "due_date" => $request->due_date,
                    ]);
                }
            });

            return response()->json(
                $transaction->load(
                    "store",
                    "purchaseDetailTransactions.product",
                    "supplier",
                    "payable"
                ),
                201
            );
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

    public function show(PurchaseTransaction $purchaseTransaction)
    {
        try {
            Gate::authorize("view", $purchaseTransaction);
            return response()->json(
                $purchaseTransaction->load(
                    "store",
                    "purchaseDetailTransactions.product",
                    "supplier",
                    "payable"
                ),
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

    public function update(
        Request $request,
        PurchaseTransaction $purchaseTransaction
    ) {
        //This is not recommended.  Updating a purchase transaction after its creation is not a standard practice.
        return response()->json(
            ["message" => "Updating purchase transactions is not allowed."],
            405
        );
    }

    public function destroy(PurchaseTransaction $purchaseTransaction)
    {
        try {
            Gate::authorize("delete", $purchaseTransaction);
            $purchaseTransaction->delete();
            return response()->json(
                ["message" => "Purchase transaction deleted"],
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
