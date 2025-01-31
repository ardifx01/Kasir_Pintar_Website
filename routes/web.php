<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellingTransactionController;
use App\Http\Controllers\PurchaseTransactionController;
use App\Http\Controllers\ReportController;

use Barryvdh\DomPDF\Facade\Pdf;

// Rute untuk menampilkan form login
Route::get("/login", [AuthController::class, "showLoginForm"])->name("login");
// Rute untuk memproses data login yang dikirim dari form
Route::post("/login", [AuthController::class, "login"]);

// Rute untuk memproses logout route
Route::post("/logout", [AuthController::class, "logout"])->name("logout");

// Rute untuk menampilkan form login
Route::get("/register", [AuthController::class, "showRegistrationForm"])->name(
    "register"
);
// Rute untuk memproses data login yang dikirim dari form
Route::post("/register", [AuthController::class, "storeRegistration"]);

// Rute untuk halaman forgot password
Route::get("/forgot-password", [
    AuthController::class,
    "showForgotPasswordForm",
])->name("forgot-password");
// Rute untuk memproses permintaan forgot password
Route::post("/forgot-password", [
    AuthController::class,
    "processForgotPassword",
]);
// Rute untuk halaman change password
Route::get("/change-password/{email}/{token}", [
    AuthController::class,
    "showChangePasswordForm",
])->name("change-password");
// Rute untuk memproses perubahan password
Route::post("/change-password", [AuthController::class, "changePassword"]);

Route::middleware(["auth:sanctum"])->group(function () {
    Route::get("/profile/setup", [ProfileController::class, "setup"])->name(
        "profile.setup"
    );
    Route::post("/profile/setup", [
        ProfileController::class,
        "storeSetup",
    ])->name("profile.setup.store"); // Route untuk menyimpan
    Route::get("/profile/edit", [ProfileController::class, "edit"])->name(
        "profile.edit"
    );
    Route::put("/profile", [ProfileController::class, "update"])->name(
        "profile.update"
    );
    Route::get("/profile", [ProfileController::class, "show"])->name(
        "profile.show"
    );
});

Route::middleware(["auth:sanctum"])
    ->prefix("dashboard")
    ->group(function () {
        Route::get("/", [DashboardController::class, "home"])->name(
            "dashboard.home"
        );
        Route::get("/product", [DashboardController::class, "product"])->name(
            "dashboard.product"
        );
        Route::get("/transaction", [
            DashboardController::class,
            "transaction",
        ])->name("dashboard.transaction");
        Route::get("/manajemen-pelanggan", [
            DashboardController::class,
            "manajemenPelanggan",
        ])->name("dashboard.customer"); // Diubah
        Route::get("/manajemen-toko", [
            DashboardController::class,
            "manajemenToko",
        ])->name("dashboard.shop"); // Diubah
        Route::get("/laporan", [DashboardController::class, "laporan"])->name(
            "dashboard.report"
        ); // Diubah
        Route::get("/setting", [DashboardController::class, "setting"])->name(
            "dashboard.setting"
        );
        Route::get("/manajemen-user", [
            DashboardController::class,
            "manajemenUser",
        ])->name("dashboard.user"); // Diubah
        Route::get("/laporan-masalah", [
            DashboardController::class,
            "laporanMasalah",
        ])->name("dashboard.issue"); // Diubah

        Route::prefix("staff")->group(function () {
            Route::get("/", [StaffController::class, "index"])->name(
                "staffs.index"
            );

            Route::get("/create", [StaffController::class, "create"])->name(
                "staffs.create"
            );

            Route::post("/", [StaffController::class, "store"])->name(
                "staffs.store"
            );

            Route::get("/{staff}/edit", [StaffController::class, "edit"])->name(
                "staffs.edit"
            );

            Route::put("/{staff}", [StaffController::class, "update"])->name(
                "staffs.update"
            );

            Route::delete("/{staff}", [
                StaffController::class,
                "destroy",
            ])->name("staffs.destroy");
        });

        Route::prefix("stores")->group(function () {
            Route::get("/", [StoreController::class, "index"])->name(
                "stores.index"
            );
            Route::get("/create", [StoreController::class, "create"])->name(
                "stores.create"
            );
            Route::post("/", [StoreController::class, "store"])->name(
                "stores.store"
            );
            Route::get("/{store}", [StoreController::class, "show"])->name(
                "stores.show"
            );
            Route::get("/{store}/edit", [StoreController::class, "edit"])->name(
                "stores.edit"
            );
            Route::put("/{store}", [StoreController::class, "update"])->name(
                "stores.update"
            );
            Route::delete("/{store}", [
                StoreController::class,
                "destroy",
            ])->name("stores.destroy");
        });

        Route::prefix("customers")->group(function () {
            Route::get("/", [CustomerController::class, "index"])->name(
                "customers.index"
            );
            Route::get("/create", [CustomerController::class, "create"])->name(
                "customers.create"
            );
            Route::post("/", [CustomerController::class, "store"])->name(
                "customers.store"
            );
            Route::get("/{customer}/edit", [
                CustomerController::class,
                "edit",
            ])->name("customers.edit");
            Route::put("/{customer}", [
                CustomerController::class,
                "update",
            ])->name("customers.update");
            Route::delete("/{customer}", [
                CustomerController::class,
                "destroy",
            ])->name("customers.destroy");
        });

        Route::prefix("suppliers")->group(function () {
            // Tambahkan rute untuk supplier
            Route::get("/", [SupplierController::class, "index"])->name(
                "suppliers.index"
            );
            Route::get("/create", [SupplierController::class, "create"])->name(
                "suppliers.create"
            );
            Route::post("/", [SupplierController::class, "store"])->name(
                "suppliers.store"
            );
            Route::get("/{supplier}/edit", [
                SupplierController::class,
                "edit",
            ])->name("suppliers.edit");
            Route::put("/{supplier}", [
                SupplierController::class,
                "update",
            ])->name("suppliers.update");
            Route::delete("/{supplier}", [
                SupplierController::class,
                "destroy",
            ])->name("suppliers.destroy");
        });

        Route::prefix("products")->group(function () {
            Route::get("/", [ProductController::class, "index"])->name(
                "products.index"
            );
            Route::get("/create", [ProductController::class, "create"])->name(
                "products.create"
            );
            Route::post("/", [ProductController::class, "store"])->name(
                "products.store"
            );
            Route::get("/store/", [ProductController::class, "show"])->name(
                "products.show"
            ); // Menggunakan store_id
            Route::get("/{product}/edit", [
                ProductController::class,
                "edit",
            ])->name("products.edit");
            Route::put("/{product}", [
                ProductController::class,
                "update",
            ])->name("products.update");
            Route::delete("/{product}", [
                ProductController::class,
                "destroy",
            ])->name("products.destroy");
        });

        Route::prefix("transactions")->group(function () {
            Route::get("/selling", [
                SellingTransactionController::class,
                "index",
            ])->name("transactions.selling");
            Route::get("/selling/{sellingTransaction}", [
                SellingTransactionController::class,
                "show",
            ])->name("transactions.detail-selling");
            Route::get("/purchasing", [
                PurchaseTransactionController::class,
                "index",
            ])->name("transactions.purchasing");
            Route::get("/purchasing/{purchaseTransaction}", [
                PurchaseTransactionController::class,
                "show",
            ])->name("transactions.detail-purchasing");
        });

        Route::prefix("reports")->group(function () {
            Route::get("/selling", [
                ReportController::class,
                "sellingReport",
            ])->name("report.selling");
            Route::get("/purchase", [
                ReportController::class,
                "purchaseReport",
            ])->name("report.purchase");
            Route::get("/receivable", [
                ReportController::class,
                "receivableReport",
            ])->name("report.receivable");
            Route::get("/payable", [
                ReportController::class,
                "payableReport",
            ])->name("report.payable");
        });

        Route::get("/selling-transactions/{sellingTransaction}/print-pdf", [
            SellingTransactionController::class,
            "printPdf",
        ])->name("selling-transactions.printPdf");
        Route::get("/purchase-transactions/{purchaseTransaction}/print-pdf", [
            PurchaseTransactionController::class,
            "printPdf",
        ])->name("purchase-transactions.printPdf");
    });
