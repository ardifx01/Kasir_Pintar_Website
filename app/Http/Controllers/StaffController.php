<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Store;
use App\Models\User;
use App\Http\Requests\StaffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize("viewAny", Staff::class);
        $staffs = Staff::with("user", "store")->get();

        //Improved store data fetching: Handles cases where user has no stores.
        $stores = Auth::user()->isAdmin()
            ? Store::all()
            : Auth::user()->stores()->get();

        $role = Auth::user()->role;
        return view("staffs.index", compact("staffs", "stores", "role"));
    }

    public function create()
    {
        $this->authorize("create", Staff::class);

        //Improved store data fetching: Handles cases where user has no stores.
        $stores = Auth::user()->isAdmin()
            ? Store::all()
            : Auth::user()->stores()->get();

        $role = Auth::user()->role;
        return view("staffs.create", compact("stores", "role"));
    }

    public function store(StaffRequest $request)
    {
        $this->authorize("create", Staff::class);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "number_phone" => $request->number_phone,
            "role" => "staff",
        ]);

        Staff::create([
            "store_id" => $request->store_id,
            "user_id" => $user->id,
            "role" => $request->role,
        ]);

        return redirect()
            ->route("staffs.index")
            ->with("success", "Staff berhasil ditambahkan.");
    }

    public function edit(Staff $staff)
    {
        $this->authorize("update", $staff);

        //Improved store data fetching: Handles cases where user has no stores.
        $stores = Auth::user()->isAdmin()
            ? Store::all()
            : Auth::user()->stores()->get();
        $role = Auth::user()->role;
        return view("staffs.edit", compact("staff", "stores", "role"));
    }

    public function update(StaffRequest $request, Staff $staff)
    {
        $this->authorize("update", $staff);

        try {
            $staff->user()->update([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "number_phone" => $request->number_phone,
            ]);

            $staff->update([
                "role" => $request->role,
                "store_id" => $request->store_id,
            ]);
            return redirect()
                ->route("staffs.index")
                ->with("success", "Staff berhasil diubah.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with("error", "Terjadi kesalahan saat memperbarui staff.");
        }
    }

    public function destroy(Staff $staff)
    {
        $this->authorize("delete", $staff);
        try {
            $staff->delete();
            return redirect()
                ->route("staffs.index")
                ->with("success", "Staff berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with("error", "Terjadi kesalahan saat menghapus staff.");
        }
    }
}
