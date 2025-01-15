<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view("auth.login");
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $credentials = $request->only("email", "password");

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended("/dashboard"); // Redirect ke halaman utama setelah login
        }

        return back()->withErrors([
            "email" => "The provided credentials are incorrect.",
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect("/login");
    }

    public function showRegistrationForm()
    {
        return view("auth.register");
    }

    public function storeRegistration(Request $request)
    {
        $request->validate(
            [
                "name" => ["required", "string", "max:255"],
                "email" => [
                    "required",
                    "string",
                    "email",
                    "max:255",
                    "unique:users",
                ],
                "password" => ["required", "string", "min:8", "confirmed"],
                "password_confirmation" => ["required", "string", "min:8"],
            ],
            [
                "password.confirmed" => "The passwords do not match.",
                "password.min" =>
                    "The password must be at least :min characters.",
                "password_confirmation.required" =>
                    "Please confirm your password.",
                "email.unique" => "This email address is already registered.",
            ]
        );

        try {
            $user = null; // Initialize $user outside the closure
            DB::transaction(function () use ($request, &$user) {
                // Pass $user by reference
                $user = User::create([
                    "name" => $request->name,
                    "email" => $request->email,
                    "password" => Hash::make($request->password),
                ]);

                Profile::create([
                    "user_id" => $user->id,
                    "gender" => "none",
                    "age" => 0,
                    "address" => "",
                    "url_image" => "",
                ]);
            });

            if ($user) {
                // Check if $user was created successfully
                Auth::login($user);
                return redirect()
                    ->route("profile.setup")
                    ->with("success", "Registration successful!");
            } else {
                return back()
                    ->withErrors(["error" => "User creation failed."])
                    ->withInput();
            }
        } catch (QueryException $e) {
            return back()
                ->withErrors([
                    "error" =>
                        "Database error during registration: " .
                        $e->getMessage(),
                ])
                ->withInput();
        } catch (\Exception $e) {
            return back()
                ->withErrors([
                    "error" =>
                        "An unexpected error occurred during registration. Please try again later.",
                ])
                ->withInput(); //Keep user input
        }
    }

    public function showForgotPasswordForm()
    {
        return view("auth.forgot-password");
    }

    public function processForgotPassword(Request $request)
    {
        $request->validate(["email" => "required|email|exists:users,email"]);

        $user = User::where("email", $request->email)->first();
        $token = Str::uuid(); // Menggunakan UUID untuk token yang lebih kuat

        $user->remember_token = $token;
        $user->save();

        return redirect()
            ->route("change-password", [
                "email" => $user->email,
                "token" => $token,
            ])
            ->with("success", "Link ganti password telah dikirim.");
    }

    public function showChangePasswordForm($email, $token)
    {
        return view("auth.reset-password", compact("email", "token"));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            "email" => "required|email|exists:users,email",
            "token" => "required",
            "current_password" => "required",
            "password" => "required|confirmed|min:8",
        ]);

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                "email" => "Pengguna tidak ditemukan.",
            ]);
        }

        if ($user->remember_token != $request->token) {
            return back()->withErrors([
                "email" => "Token tidak valid.",
            ]);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                "current_password" => "Password lama salah.",
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->remember_token = null;
        $user->save();

        return redirect("/login")->with("success", "Password berhasil diubah.");
    }
}
