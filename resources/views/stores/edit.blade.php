@extends('layouts.app')

@section('title', 'Edit Toko')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Edit Toko: {{ $store->name }}</h1>

        <x-card>
            <x-form action="{{ route('stores.update', $store->id) }}" method="PUT">
                <x-input label="Nama Toko" name="name" id="name" :value="old('name', $store->name)" required />
                <x-input label="Nomor Telepon" name="number_phone" id="number_phone" type="tel" :value="old('number_phone', $store->number_phone)" required />
                <x-input label="Kode Pos" name="postal_code" id="postal_code" :value="old('postal_code', $store->postal_code)" required />
                <x-input label="Alamat" name="address" id="address" :value="old('address', $store->address)" required />
                <x-input-file label="Gambar Toko (Opsional)" name="url_image" />
                <div class="mb-3">
                    @if($store->url_image)
                        <img src="{{ $store->url_image }}" alt="{{ $store->name }}" class="img-fluid mb-2" style="max-height: 100px;">
                    @endif
                </div>
            </x-form>
        </x-card>
    </div>
</div>
@endsection
