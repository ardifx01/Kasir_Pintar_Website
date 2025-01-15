<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile; // Ambil profil dari user yang sedang login

        return view("profile.show", compact("profile", "user")); // Ganti 'profile.show' dengan nama view Anda
    }

    public function setup()
    {
        $user = Auth::user();
        return view("profile.setup", compact("user"));
    }

    public function storeSetup(Request $request)
    {
        $request->validate([
            "gender" => "required|string",
            "age" => "required|integer",
            "address" => "required|string",
            "url_image" => "required|file|image|mimes:jpeg,png,jpg,gif",
        ]);

        try {
            $user = Auth::user();
            $profile = $user->profile;

            if ($request->hasFile("url_image")) {
                if ($profile && $profile->url_image) {
                    Storage::disk("public")->delete($profile->url_image);
                }
                $imagePath = $request
                    ->file("url_image")
                    ->store("profile_images", "public");
                $profile->url_image = $imagePath;
            }

            $dataToUpdate = $request->only("gender", "age", "address");
            if (
                $profile->url_image !== null ||
                $request->hasFile("url_image")
            ) {
                $dataToUpdate["url_image"] = $profile->url_image;
            }

            $profile->update($dataToUpdate);

            return redirect()
                ->route("login")
                ->with("success", "Profile updated successfully!");
        } catch (\Illuminate\Database\QueryException $e) {
            return back()
                ->withErrors(["error" => "Database error: " . $e->getMessage()])
                ->withInput();
        } catch (\Exception $e) {
            \Log::error("Profile Update Error: " . $e->getMessage());
            return back()
                ->withErrors([
                    "error" =>
                        "An unexpected error occurred. Please try again later.",
                ])
                ->withInput();
        }
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;
        return view("profile.edit", compact("profile", "user")); // Ganti 'profile.edit' dengan nama view Anda
    }

    public function update(Request $request)
    {
        $request->validate([
            "gender" => "required|string",
            "age" => "nullable|integer",
            "address" => "nullable|string",
            "url_image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048", // Validasi gambar
        ]);

        $user = Auth::user();
        $profile = $user->profile;

        // Handle image upload
        if ($request->hasFile("url_image")) {
            // Hapus gambar lama jika ada
            if ($profile && $profile->url_image) {
                Storage::disk("public")->delete($profile->url_image);
            }
            $imagePath = $request
                ->file("url_image")
                ->store("profile_images", "public"); // Simpan gambar ke storage/app/public/profile_images
            $profile->url_image = $imagePath;
        }

        $profile->update($request->all());

        return redirect()
            ->route("profile.show")
            ->with("success", "Profil berhasil diperbarui."); // Ganti 'profile.show' dengan nama route Anda
    }
}
