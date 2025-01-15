<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("stores", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("owner_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table->string("name");
            $table->string("number_phone")->nullable()->default("");
            $table->string("postal_code");
            $table->string("address");
            $table->string("url_image");
            $table->timestamps();
        });

        Schema::create("staffs", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table
                ->foreignId("user_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table->string("role")->nullable();
            $table->timestamps();
        });

        Schema::create("suppliers", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table->string("name");
            $table->string("number_phone")->nullable()->default("");
            $table->string("address");
            $table->string("email");
            $table->timestamps();
        });

        Schema::create("customers", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("store_id")
                ->constrained("stores")
                ->onDelete("cascade");
            $table->string("name");
            $table->string("number_phone")->nullable()->default("");
            $table->string("address");
            $table->string("email");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("customers");
        Schema::dropIfExists("suppliers");
        Schema::dropIfExists("staffs");
        Schema::dropIfExists("stores");
    }
};
