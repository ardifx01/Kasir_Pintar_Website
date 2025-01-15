<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile()->first();
        if (!$profile) {
            return response()->json(["message" => "Profile not found"], 404);
        }
        return response()->json(
            array_merge(
                $user->only(["id", "name", "email", "number_phone"]),
                $profile->toArray()
            )
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "gender" => [
                "required",
                "string",
                Rule::in(["male", "female", "none"]),
            ],
            "age" => ["required", "integer", "min:0"],
            "address" => ["required", "string"],
            "image" => [
                "nullable",
                "image",
                "mimes:jpeg,png,jpg,gif",
                "max:2048",
            ],
            "number_phone" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $user = Auth::user();
        $profile = $user->profile()->firstOrCreate([
            "age" => 0,
            "address" => "",
            "gender" => "none",
            "url_image" => "",
        ]);

        if ($request->hasFile("image")) {
            try {
                if ($profile->url_image) {
                    Storage::disk("public")->delete($profile->url_image);
                }
                $imagePath = $request
                    ->file("image")
                    ->store("profile_images", "public");
                $profile->url_image = $imagePath;
            } catch (\Exception $e) {
                return response()->json(
                    ["error" => "Gagal mengunggah gambar: " . $e->getMessage()],
                    500
                );
            }
        }

        $profile->fill($request->only(["gender", "age", "address"]));
        $profile->save();

        //Update number_phone di model User
        $user->number_phone = $request->number_phone;
        $user->save();

        return response()->json(
            array_merge(
                $user->only(["id", "name", "email", "number_phone"]),
                $profile->toArray()
            ),
            201
        );
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "gender" => [
                    "nullable",
                    "string",
                    Rule::in(["male", "female", "other"]),
                ],
                "age" => ["nullable", "integer", "min:0"],
                "address" => ["nullable", "string"],
                "number_phone" => ["nullable", "string"],
            ]);

            $user = Auth::user();
            $profile = $user->profile()->first();
            if (!$profile) {
                return response()->json(
                    ["message" => "Profile not found"],
                    404
                );
            }
            $profile->fill($validatedData);
            $profile->save();
            return response()->json(
                array_merge(
                    $user->only(["id", "name", "email", "number_phone"]),
                    $profile->toArray()
                ),
                200
            );
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }

    public function updateImageProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "image" => [
                "required",
                "image",
                "mimes:jpeg,png,jpg,gif",
                "max:2048",
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $user = Auth::user();
        $profile = $user->profile()->first();
        if (!$profile) {
            return response()->json(["message" => "Profile not found"], 404);
        }

        if ($profile->url_image) {
            Storage::disk("public")->delete($profile->url_image);
        }

        $imagePath = $request->file("image")->store("profile_images", "public");
        $profile->url_image = $imagePath;
        $profile->save();

        return response()->json(
            array_merge(
                $user->only(["id", "name", "email", "number_phone"]),
                $profile->toArray()
            ),
            200
        );
    }

    public function destroy()
    {
        $profile = auth()->user()->profile()->first();
        if (!$profile) {
            return response()->json(["message" => "Profile not found"], 404);
        }
        Storage::disk("public")->delete($profile->url_image);
        $profile->delete();
        return response()->json(["message" => "Profile deleted"]);
    }
}
