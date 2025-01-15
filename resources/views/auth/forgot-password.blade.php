@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<section class="section-center">
    <x-card class="mt-5 w-400">
        <h3 class="text-center">Forgot Password</h3>
        <p class="description mb-4">Masukkan email Anda untuk menerima link reset password.</p>
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <x-form action="{{ route('forgot-password') }}" method="POST">
            <x-input type="email" id="email" name="email" label="Email" placeholder="Masukkan email Anda" :value="old('email')" required autofocus />
        </x-form>
        <div class="text-center mt-3">
            <p>Ingat Password Anda? <a href="{{ route('login') }}" class="text-decoration-none">Masuk sekarang</a></p>
        </div>
    </x-card>
</section>
@endsection
