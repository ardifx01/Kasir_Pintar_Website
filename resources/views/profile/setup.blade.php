@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<section class="section-center">
    <x-card class="mt-5 w-400">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <x-form action="{{ route('profile.setup.store') }}" method="POST" enctype="multipart/form-data" submit-button-text="Simpan">
            <livewire:profile-picture />
            <x-select id="gender" label="Jenis Kelamin" name="gender" :options="['none' => 'None', 'male' => 'Laki-laki', 'female' => 'Perempuan']" />
            <x-input type="number" label="Usia" name="age" id="age" placeholder="masukkan umur anda"/>
            <x-input type="text" label="Alamat" name="address" id="address" placeholder="masukkan alamat anda"/>
        </x-form>
    </x-card>
</section>
@endsection
