<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        $stores = Store::where("owner_id", Auth::id())->get(); // Mengambil toko milik user yang sedang login

        return view("stores.index", compact("stores", "role"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = Auth::user()->role;
        return view("stores.create", compact("role"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile("url_image")) {
            $image = $request->file("url_image");
            $imagePath = $image->store("store_images", "public");
            $validatedData["url_image"] = Storage::url($imagePath);
        }

        $validatedData["owner_id"] = Auth::id();
        Store::create($validatedData);

        return redirect()
            ->route("stores.index")
            ->with("success", "Toko berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     *
     *  This is optional, depending on your application's needs.  If you need to show individual store details, implement this.
     */
    public function show(Store $store)
    {
        $this->authorize("view", $store); //Authorization
        $role = Auth::user()->role;
        return view("stores.show", compact("store", "role")); //Create stores.show.blade.php
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        $this->authorize("update", $store); //Authorization
        $role = Auth::user()->role;
        return view("stores.edit", compact("store", "role"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Store $store)
    {
        $this->authorize("update", $store); //Authorization
        $validatedData = $request->validated();

        if ($request->hasFile("url_image")) {
            // Hapus gambar lama jika ada
            if ($store->url_image) {
                Storage::disk("public")->delete(
                    str_replace(Storage::url(""), "", $store->url_image)
                );
            }
            $image = $request->file("url_image");
            $imagePath = $image->store("store_images", "public");
            $validatedData["url_image"] = Storage::url($imagePath);
        }

        $store->update($validatedData);

        return redirect()
            ->route("stores.index")
            ->with("success", "Toko berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * This is optional, and requires adding delete functionality to your views and potentially handling deletion of associated data.
     */
    public function destroy(Store $store)
    {
        $this->authorize("delete", $store); //Authorization
        // Hapus gambar jika ada
        if ($store->url_image) {
            Storage::disk("public")->delete(
                str_replace(Storage::url(""), "", $store->url_image)
            );
        }
        $store->delete();
        return redirect()
            ->route("stores.index")
            ->with("success", "Toko berhasil dihapus.");
    }
}
