@extends('layouts.app')

@section('title', 'Register')

@section('content')
<section class="section-center flex column">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <x-card class="mt-5 w-400">
        <h3 class="text-center">Register</h3>
        <p class="description mb-4">Daftar akun baru menggunakan formulir di bawah ini.</p>
        <x-form action="{{ route('register') }}" method="POST">
            <x-input type="text" id="name" name="name" label="Nama" placeholder="Masukkan nama lengkap" :value="old('name')" required autofocus />
            <x-input type="email" id="email" name="email" label="Email" placeholder="Masukkan email" :value="old('email')" required />
            <livewire:password-input :id="'password'" :label="'Password'" :name="'password'" placeholder="Masukkan password" />
            <livewire:password-input :id="'password_confirmation'" :label="'Konfirmasi Password'" :name="'password_confirmation'" type="password" placeholder="Masukkan ulang password" />
        </x-form>
        <div class="text-center mt-3">
            <p>Sudah punya akun ? <a href="{{ route('login') }}" class="text-decoration-none">Masuk sekarang</a></p>
        </div>
    </x-card>
</section>
@endsection
