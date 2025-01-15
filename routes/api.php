<?php
use App\Http\Middleware\AuthenticateApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PurchaseTransactionController;
use App\Http\Controllers\Api\SellingTransactionController;
use App\Http\Controllers\Api\ReportController;

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
Route::post("/logout", [AuthController::class, "logout"])->middleware([
    AuthenticateApi::class,
    "auth:sactum",
]);
Route::post("/forgot-password", [AuthController::class, "forgotPassword"]);
Route::post("/change-password", [AuthController::class, "changePassword"]);

Route::middleware(["auth:sanctum"])->group(function () {
    Route::prefix("profile")->group(function () {
        Route::get("/", [ProfileController::class, "show"]);
        Route::post("/", [ProfileController::class, "store"]);
        Route::patch("/", [ProfileController::class, "update"]); // Update profil (tanpa gambar)
        Route::post("/image", [ProfileController::class, "updateImageProfile"]); // Update hanya gambar
        Route::delete("/", [ProfileController::class, "destroy"]);
    });

    Route::prefix("stores")->group(function () {
        Route::get("/", [StoreController::class, "index"]);
        Route::get("/{store}", [StoreController::class, "show"]);
        Route::post("/", [StoreController::class, "store"]);
        Route::patch("/{store}", [StoreController::class, "update"]);
        Route::post("/{store}/image", [StoreController::class, "updateImage"]); //Updated route
        Route::delete("/{store}", [StoreController::class, "destroy"]);
    });

    Route::prefix("staffs")->group(function () {
        Route::get("/", [StaffController::class, "index"]);
        Route::get("/store/{storeId}", [
            StaffController::class,
            "indexByStore",
        ]);
        Route::post("/", [StaffController::class, "store"]);
        Route::get("/{staff}", [StaffController::class, "show"]);
        Route::put("/{staff}", [StaffController::class, "update"]);
        Route::delete("/{staff}", [StaffController::class, "destroy"]);
    });

    Route::prefix("suppliers")->group(function () {
        Route::get("/", [SupplierController::class, "index"]);
        Route::get("/store/{storeId}", [
            SupplierController::class,
            "indexByStore",
        ]);
        Route::post("/", [SupplierController::class, "store"]);
        Route::get("/{supplier}", [SupplierController::class, "show"]);
        Route::put("/{supplier}", [SupplierController::class, "update"]);
        Route::delete("/{supplier}", [SupplierController::class, "destroy"]);
    });

    Route::prefix("customers")->group(function () {
        Route::get("/", [CustomerController::class, "index"]);
        Route::get("/store/{storeId}", [
            SupplierController::class,
            "indexByStore",
        ]);
        Route::post("/", [CustomerController::class, "store"]);
        Route::get("/{customer}", [CustomerController::class, "show"]);
        Route::put("/{customer}", [CustomerController::class, "update"]);
        Route::delete("/{customer}", [CustomerController::class, "destroy"]);
    });

    Route::prefix("products")->group(function () {
        Route::get("/", [ProductController::class, "index"]);
        Route::get("/store/{storeId}", [
            ProductController::class,
            "indexByStore",
        ]);
        Route::post("/", [ProductController::class, "store"]);
        Route::get("/{product}", [ProductController::class, "show"]);
        Route::post("/{product}", [ProductController::class, "update"]);
        Route::post("/{product}/image", [
            ProductController::class,
            "updateImage",
        ]);
        Route::delete("/{product}", [ProductController::class, "destroy"]);
    });

    Route::get("/categories", [ProductController::class, "categoryProducts"]);

    Route::prefix("transactions")->group(function () {
        Route::get("/selling", [SellingTransactionController::class, "index"]);
        Route::post("/selling", [SellingTransactionController::class, "store"]);
        Route::get("/selling/{sellingTransaction}", [
            SellingTransactionController::class,
            "show",
        ]);
        Route::delete("/selling/{sellingTransaction}", [
            SellingTransactionController::class,
            "destroy",
        ]);
        Route::get("/purchase", [
            PurchaseTransactionController::class,
            "index",
        ]);
        Route::post("/purchase", [
            PurchaseTransactionController::class,
            "store",
        ]);
        Route::get("/purchase/{purchaseTransaction}", [
            PurchaseTransactionController::class,
            "show",
        ]);
        Route::delete("/purchase/{purchaseTransaction}", [
            PurchaseTransactionController::class,
            "destroy",
        ]);
    });

    Route::prefix("reports")->group(function () {
        Route::get("/selling/{storeId}", [
            ReportController::class,
            "sellingReport",
        ]);
        Route::get("/purchase/{storeId}", [
            ReportController::class,
            "purchaseReport",
        ]);
        Route::get("/receivables/{storeId}", [
            ReportController::class,
            "receivablesReport",
        ]);
        Route::get("/payables/{storeId}", [
            ReportController::class,
            "payablesReport",
        ]);
        Route::get("/total-income/{storeId}", [
            ReportController::class,
            "totalIncomeReport",
        ]);
        Route::get("/top-selling/{storeId}", [
            ReportController::class,
            "topSellingProducts",
        ]);
    });
});
