<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Profile;
use App\Policies\ProfilePolicy;
use App\Models\Staff;
use App\Policies\StaffPolicy;
use App\Models\Store;
use App\Policies\StorePolicy;
use App\Models\Product;
use App\Policies\ProductPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Profile::class => ProfilePolicy::class,
        Staff::class => StaffPolicy::class,
        Store::class => StorePolicy::class,
        Product::class => ProductPolicy::class,
    ];

    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
