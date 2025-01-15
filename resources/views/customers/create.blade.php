@extends('layouts.app')

@section('title', 'Tambah Customer')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1>Tambah Customer</h1>

        <x-card>
            <x-form action="{{ route('customers.store') }}" method="POST">
                <x-select label="Toko" name="store_id" id="store_id" :options="$stores->pluck('name', 'id')->toArray()" required />
                <x-input label="Nama" name="name" id="name" :value="old('name')" required />
                <x-input label="Nomor Telepon" name="number_phone" id="number_phone" :value="old('number_phone')" />
                <x-input label="Alamat" name="address" id="address" :value="old('address')" />
                <x-input label="Email" name="email" id="email" type="email" :value="old('email')" />
            </x-form>
        </x-card>
    </div>
</div>
@endsection
