<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("selling_transactions", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table->decimal("total_discount", 10, 2)->default(0.0);
            $table->decimal("total_tax", 10, 2)->default(0.0);
            $table->boolean("is_debt")->default(false);
            $table->text("description")->nullable();
            $table->enum("payment_method", ["cash", "transfer"]);
            $table->decimal("total_amount", 10, 2);
            $table->decimal("amount_paid", 10, 2);
            $table->decimal("change_amount", 10, 2)->default(0.0);
            $table->enum("transaction_status", ["done", "pending"]);
            $table->timestamps();
        });

        Schema::create("selling_detail_transactions", function (
            Blueprint $table
        ) {
            $table->id();
            $table
                ->foreignId("transaction_id")
                ->constrained("selling_transactions")
                ->onDelete("cascade");
            $table
                ->foreignId("product_id")
                ->constrained("products")
                ->onDelete("cascade");
            $table->integer("quantity");
            $table->decimal("subtotal", 10, 2);
            $table->timestamps();
        });

        Schema::create("purchase_transactions", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table->decimal("total_discount", 10, 2)->default(0.0);
            $table->decimal("total_tax", 10, 2)->default(0.0);
            $table->boolean("is_debt")->default(false);
            $table->text("description")->nullable();
            $table->enum("payment_method", ["cash", "transfer"]);
            $table->decimal("total_amount", 10, 2);
            $table->decimal("amount_paid", 10, 2);
            $table->decimal("change_amount", 10, 2)->nullable();
            $table->enum("transaction_status", ["done", "pending"]);
            $table->timestamps();
        });

        Schema::create("purchase_detail_transactions", function (
            Blueprint $table
        ) {
            $table->id();
            $table
                ->foreignId("transaction_id")
                ->constrained("purchase_transactions")
                ->onDelete("cascade");
            $table
                ->foreignId("product_id")
                ->constrained("products")
                ->onDelete("cascade");
            $table->integer("quantity");
            $table->decimal("subtotal", 10, 2);
            $table->timestamps();
        });

        Schema::create("receivables", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("transaction_id")
                ->constrained("selling_transactions")
                ->onDelete("cascade");
            $table
                ->foreignId("customer_id")
                ->constrained("customers")
                ->onDelete("cascade");
            $table->decimal("amount_due", 10, 2);
            $table->enum("payment_status", ["paid", "unpaid"]);
            $table->date("due_date");
            $table->timestamps();
        });

        Schema::create("payables", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("transaction_id")
                ->constrained("purchase_transactions")
                ->onDelete("cascade");
            $table
                ->foreignId("supplier_id")
                ->constrained("suppliers")
                ->onDelete("cascade");
            $table->decimal("amount_due", 10, 2);
            $table->enum("payment_status", ["paid", "unpaid"]);
            $table->date("due_date");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("payables");
        Schema::dropIfExists("receivables");
        Schema::dropIfExists("purchase_detail_transactions");
        Schema::dropIfExists("purchase_transactions");
        Schema::dropIfExists("selling_detail_transactions");
        Schema::dropIfExists("selling_transactions");
    }
};
