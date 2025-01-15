<?php

namespace App\Providers;

use App\Models\Staff;
use App\Models\Store;
use App\Policies\StaffPolicy;
use App\Policies\StorePolicy;
use Illuminate\Support\ServiceProvider;
use App\Models\Profile;
use App\Policies\ProfilePolicy;
use App\Models\Product;
use App\Policies\ProductPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Profile::class, ProfilePolicy::class);
        Gate::policy(Staff::class, StaffPolicy::class);
        Gate::policy(Store::class, StorePolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
    }
}
