@extends('layouts.app')

@section('title', 'Tambah Toko')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Tambah Toko Baru</h1>

        <x-card>
            <x-form action="{{ route('stores.store') }}" method="POST">
                <x-input label="Nama Toko" name="name" id="name" :value="old('name')" required />
                <x-input label="Nomor Telepon" name="number_phone" id="number_phone" type="tel" :value="old('number_phone')" required />
                <x-input label="Kode Pos" name="postal_code" id="postal_code" :value="old('postal_code')" required />
                <x-input label="Alamat" name="address" id="address" :value="old('address')" required />
                <x-input-file label="Gambar Toko" name="url_image" id="url_image" />
            </x-form>
        </x-card>
    </div>
</div>
@endsection
