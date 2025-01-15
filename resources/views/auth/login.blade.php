@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="section-center flex column">
    <x-card class="mt-5 w-400">
        <h3 class="text-center">Login</h3>
        <p class="description mb-4">Silakan masuk menggunakan email dan password Anda.</p>
        <x-form action="{{ route('login') }}" method="POST">
            <x-input type="email" id="email" name="email" label="Email" placeholder="Masukkan email" :value="old('email')" required autofocus />
            <livewire:password-input :id="'password'" :label="'Password'" :name="'password'" placeholder="Masukkan password" />
            <div class="mb-3 form-check text-right">
                <a href="/forgot-password" class="text-decoration-none">Lupa Password ?</a>
            </div>
        </x-form>
        <div class="text-center mt-3">
            <p>Belum punya akun ? <a href="/register" class="text-decoration-none">Daftar sekarang</a></p>
        </div>
    </x-card>
</section>
@endsection
