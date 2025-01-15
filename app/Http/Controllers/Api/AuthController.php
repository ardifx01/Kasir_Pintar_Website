<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users",
            ],
            "password" => ["required", "string", "min:8", "confirmed"],
            "password_confirmation" => ["required", "string"],
        ];

        $messages = [
            // pesan error dalam bahasa Indonesia
            "name.required" => "Nama wajib diisi.",
            "name.string" => "Nama harus berupa teks.",
            "name.max" => "Nama maksimal :max karakter.",
            "email.required" => "Email wajib diisi.",
            "email.string" => "Email harus berupa teks.",
            "email.email" => "Format email tidak valid.",
            "email.max" => "Email maksimal :max karakter.",
            "email.unique" => "Email sudah terdaftar.",
            "password.required" => "Password wajib diisi.",
            "password.string" => "Password harus berupa teks.",
            "password.min" => "Password minimal :min karakter.",
            "password.confirmed" => "Konfirmasi password tidak sesuai.",
            "password_confirmation.required" =>
                "Konfirmasi password wajib diisi.",
            "password_confirmation.string" =>
                "Konfirmasi password harus berupa teks.",
        ];

        try {
            $validated = Validator::validate(
                $request->all(),
                $rules,
                $messages
            );
            $validated["password"] = Hash::make($validated["password"]);
            $user = User::create($validated);

            return response()->json(
                [
                    "message" => "Registrasi berhasil",
                    "user" => $user,
                ],
                201
            );
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        } catch (QueryException $ex) {
            if ($ex->getCode() == "23000") {
                return response()->json(
                    ["error" => "Email sudah terdaftar"],
                    422
                );
            } else {
                return response()->json(
                    ["error" => "Terjadi kesalahan saat registrasi."],
                    500
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Terjadi kesalahan saat registrasi."],
                500
            );
        }
    }

    public function login(Request $request)
    {
        $rules = [
            "email" => ["required", "email"],
            "password" => ["required", "string", "min:8"],
        ];

        $messages = [
            "email.required" => "Email wajib diisi.",
            "email.email" => "Format email tidak valid.",
            "password.required" => "Password wajib diisi.",
            "password.min" => "Password minimal :min karakter.",
            "password.string" => "Password harus berupa teks.",
        ];

        try {
            $validated = Validator::validate(
                $request->all(),
                $rules,
                $messages
            );
            if (!Auth::attempt($validated)) {
                throw ValidationException::withMessages([
                    "email" => ["Email atau password salah."],
                ]);
            }

            $user = Auth::user();
            $token = $user->createToken("auth_token")->plainTextToken;

            return response()->json([
                "message" => "Login berhasil",
                "access_token" => $token,
                "token_type" => "Bearer",
                "user" => $user,
            ]);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Terjadi kesalahan saat login."],
                500
            );
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "Logout berhasil"]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email|exists:users,email",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        try {
            $user = User::where("email", $request->email)->first();
            if (!$user) {
                return response()->json(["errors" => "User not found"], 404);
            }
            $token = Str::uuid();
            $user->remember_token = $token;
            $user->save();

            return response()->json(
                [
                    "message" => "Password reset link sent successfully!",
                    "token" => $token, //Return the token for the client to use.
                    "email" => $user->email, //Return the email for clarity.
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "An error occurred: " . $e->getMessage()],
                500
            );
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email|exists:users,email",
            "token" => "required", // Tambahkan validasi token
            "old_password" => "required",
            "new_password" => "required|confirmed|min:8",
            "new_password_confirmation" => "required|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        try {
            $user = User::where("email", $request->email)->first();
            if (!$user) {
                return response()->json(["error" => "User not found"], 404);
            }

            // Verifikasi token
            if ($user->remember_token !== $request->token) {
                return response()->json(["error" => "Invalid token"], 401);
            }

            // Verifikasi old password
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(
                    ["error" => "Incorrect current password"],
                    422
                );
            }

            $user->password = Hash::make($request->new_password);
            $user->remember_token = null; // Hapus token setelah password diubah
            $user->save();

            return response()->json(
                ["message" => "Password changed successfully!"],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "An error occurred: " . $e->getMessage()],
                500
            );
        }
    }
}
