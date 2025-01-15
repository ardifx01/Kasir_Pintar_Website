<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SellingTransaction;
use App\Models\PurchaseTransaction;
use App\Models\Receivable;
use App\Models\Payable;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    public function sellingReport(Request $request, $storeId)
    {
        try {
            Gate::authorize("viewAny", SellingTransaction::class);
            if (
                !Auth::user()->isAdmin() &&
                !Auth::user()->ownsStore($storeId)
            ) {
                throw new AuthorizationException("Unauthorized");
            }
            $startDate = Carbon::parse(
                $request->input("start_date", now()->subDays(30))
            )->startOfDay();
            $endDate = Carbon::parse(
                $request->input("end_date", now())
            )->endOfDay();

            $sellingReport = SellingTransaction::where("store_id", $storeId)
                ->whereBetween("created_at", [$startDate, $endDate])
                ->with("sellingDetailTransactions.product")
                ->get();

            return response()->json($sellingReport, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function purchaseReport(Request $request, $storeId)
    {
        try {
            Gate::authorize("viewAny", PurchaseTransaction::class);
            if (
                !Auth::user()->isAdmin() &&
                !Auth::user()->ownsStore($storeId)
            ) {
                throw new AuthorizationException("Unauthorized");
            }
            $startDate = Carbon::parse(
                $request->input("start_date", now()->subDays(30))
            )->startOfDay();
            $endDate = Carbon::parse(
                $request->input("end_date", now())
            )->endOfDay();

            $purchaseReport = PurchaseTransaction::where("store_id", $storeId)
                ->whereBetween("created_at", [$startDate, $endDate])
                ->with("purchaseDetailTransactions.product")
                ->get();

            return response()->json($purchaseReport, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function receivablesReport(Request $request, $storeId)
    {
        try {
            Gate::authorize("viewAny", Receivable::class);
            if (
                !Auth::user()->isAdmin() &&
                !Auth::user()->ownsStore($storeId)
            ) {
                throw new AuthorizationException("Unauthorized");
            }

            $receivables = Receivable::whereHas("transaction", function (
                $query
            ) use ($storeId) {
                $query->where("store_id", $storeId);
            })->get();

            return response()->json($receivables, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function payablesReport(Request $request, $storeId)
    {
        try {
            Gate::authorize("viewAny", Payable::class);
            if (
                !Auth::user()->isAdmin() &&
                !Auth::user()->ownsStore($storeId)
            ) {
                throw new AuthorizationException("Unauthorized");
            }

            $payables = Payable::whereHas("transaction", function ($query) use (
                $storeId
            ) {
                $query->where("store_id", $storeId);
            })->get();

            return response()->json($payables, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function totalIncomeReport(Request $request, $storeId)
    {
        try {
            Gate::authorize("viewAny", SellingTransaction::class);
            if (
                !Auth::user()->isAdmin() &&
                !Auth::user()->ownsStore($storeId)
            ) {
                throw new AuthorizationException("Unauthorized");
            }
            $startDate = Carbon::parse(
                $request->input("start_date", now()->subDays(30))
            )->startOfDay();
            $endDate = Carbon::parse(
                $request->input("end_date", now())
            )->endOfDay();

            $totalIncome = SellingTransaction::where("store_id", $storeId)
                ->whereBetween("created_at", [$startDate, $endDate])
                ->sum("total_amount");

            return response()->json(["total_income" => $totalIncome], 200);
        } catch (AuthorizationException $e) {
            return response()->json(["error" => "Unauthorized"], 403);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Server Error: " . $e->getMessage()],
                500
            );
        }
    }

    public function topSellingProducts(Request $request, $storeId)
    {
        try {
            Gate::authorize("viewAny", SellingTransaction::class);
            if (
                !Auth::user()->isAdmin() &&
                !Auth::user()->ownsStore($storeId)
            ) {
                throw new AuthorizationException("Unauthorized");
            }
            $startDate = Carbon::parse(
                $request->input("start_date", now()->subDays(30))
            )->startOfDay();
            $endDate = Carbon::parse(
                $request->input("end_date", now())
            )->endOfDay();

            $topSellingProducts = DB::table("selling_detail_transactions")
                ->join(
                    "products",
                    "selling_detail_transactions.product_id",
                    "=",
                    "products.id"
                )
                ->where("selling_detail_transactions.transaction_id", function (
                    $query
                ) use ($storeId, $startDate, $endDate) {
                    $query
                        ->select("id")
                        ->from("selling_transactions")
                        ->where("store_id", $storeId)
                        ->whereBetween("created_at", [$startDate, $endDate]);
                })
                ->select(
                    "products.name_product",
                    DB::raw(
                        "SUM(selling_detail_transactions.quantity) as total_sold"
                    )
                )
                ->groupBy("products.name_product")
                ->orderBy("total_sold", "desc")
                ->limit(5)
                ->get();

            return response()->json($topSellingProducts, 200);
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
