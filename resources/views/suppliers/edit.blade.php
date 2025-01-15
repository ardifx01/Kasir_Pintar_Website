@extends('layouts.app')

@section('title', 'Ubah Supplier')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Ubah Supplier</h1>

        <x-card>
            <x-form action="{{ route('suppliers.update', $supplier) }}" method="PUT">
                <x-select label="Toko" name="store_id" id="store_id" :options="$stores->pluck('name', 'id')->toArray()" :selected="$supplier->store_id" required />
                <x-input label="Nama" name="name" id="name" :value="$supplier->name" required />
                <x-input label="Nomor Telepon" name="number_phone" id="number_phone" :value="$supplier->number_phone" />
                <x-input label="Alamat" name="address" id="address" :value="$supplier->address" />
                <x-input label="Email" name="email" id="email" type="email" :value="$supplier->email" />
            </x-form>
        </x-card>
    </div>
</div>
@endsection
