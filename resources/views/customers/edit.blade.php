@extends('layouts.app')

@section('title', 'Ubah Customer')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Ubah Customer</h1>

        <x-card>
            <x-form action="{{ route('customers.update', $customer) }}" method="PUT">
                <x-select label="Toko" name="store_id" id="store_id" :options="$stores->pluck('name', 'id')->toArray()" :selected="$customer->store_id" required />
                <x-input label="Nama" name="name" id="name" :value="$customer->name" required />
                <x-input label="Nomor Telepon" name="number_phone" id="number_phone" :value="$customer->number_phone" />
                <x-input label="Alamat" name="address" id="address" :value="$customer->address" />
                <x-input label="Email" name="email" id="email" type="email" :value="$customer->email" />
            </x-form>
        </x-card>
    </div>
</div>
@endsection
