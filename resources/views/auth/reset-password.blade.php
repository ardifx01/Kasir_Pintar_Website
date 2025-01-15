@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<section class="section-center">
    <x-card class="mt-5 w-400">
        <h3 class="text-center">Reset Password</h3>
        <p class="description mb-4">Buat password baru untuk akun Anda.</p>
        @if ($errors->any())
            <div class="alert alert-danger h-fit">
                @foreach ($errors->all() as $error)
                    <p class="text-center">{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <x-form action="/change-password" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">
            <livewire:password-input :id="'current_password'" :label="'Password'" :name="'current_password'" placeholder="Masukkan password lama" />
            <livewire:password-input :id="'password'" :label="'Password'" :name="'password'" placeholder="Masukkan password baru" />
            <livewire:password-input :id="'password_confirmation'" :label="'Konfirmasi Password'" :name="'password_confirmation'" type="password" placeholder="Masukkan ulang password baru" />
        </x-form>
    </x-card>
</section>
@endsection
