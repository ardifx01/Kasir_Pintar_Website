<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public string $role;

    public function __construct(string $role = "staff")
    {
        $this->role = $role;
    }

    public function getMenuItems(): array
    {
        $menuItems = [
            [
                "label" => "Dashboard",
                "route" => "dashboard.home",
                "activeRoute" => ["dashboard.home"],
                "permission" => "view dashboard",
                "icon" => "ri-dashboard-line",
                "roles" => ["owner", "staff", "admin"],
            ],
            [
                "label" => "Transaction",
                "route" => "dashboard.transaction",
                "activeRoute" => [
                    "dashboard.transaction",
                    "transactions.selling",
                ],
                "permission" => "view transaction",
                "icon" => "ri-exchange-dollar-line",
                "roles" => ["owner", "staff"],
            ],
            [
                "label" => "Product",
                "route" => "dashboard.product",
                "activeRoute" => ["dashboard.product"],
                "permission" => "view products",
                "icon" => "ri-box-1-line",
                "roles" => ["owner", "staff"],
            ],
            [
                "label" => "Manajemen Pelanggan",
                "route" => "dashboard.customer",
                "activeRoute" => ["dashboard.customer"],
                "permission" => "manage customers",
                "icon" => "ri-user-line",
                "roles" => ["owner", "staff"],
            ],
            [
                "label" => "Manajemen Toko",
                "route" => "dashboard.shop",
                "activeRoute" => ["stores.index", "dashboard.shop"],
                "permission" => "manage shops",
                "icon" => "ri-store-2-line",
                "roles" => ["owner"],
            ],
            [
                "label" => "Laporan Transaksi",
                "route" => "dashboard.report",
                "activeRoute" => ["dashboard.report"],
                "permission" => "view reports",
                "icon" => "ri-bar-chart-line",
                "roles" => ["owner", "staff"],
            ],
            [
                "label" => "Manajemen User",
                "route" => "dashboard.user",
                "activeRoute" => ["dashboard.user"],
                "permission" => "manage users",
                "icon" => "ri-user-add-line",
                "roles" => ["admin"],
            ],
            [
                "label" => "Laporan Masalah",
                "route" => "dashboard.issue",
                "activeRoute" => ["dashboard.issue"],
                "permission" => "view issues",
                "icon" => "ri-alert-line",
                "roles" => ["admin"],
            ],
        ];

        return collect($menuItems)
            ->filter(function ($item) {
                return in_array($this->role, $item["roles"]);
            })
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view("components.sidebar", [
            "menuItems" => $this->getMenuItems(),
        ]);
    }
}
