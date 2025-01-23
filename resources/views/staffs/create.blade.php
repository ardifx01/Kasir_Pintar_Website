@extends('layouts.app')

@section('title', 'Tambah Staff')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Tambah Staff</h1>

        <x-card>
            <x-form action="{{ route('staffs.store') }}" method="POST">
                <x-input label="Nama" name="name" id="name" :value="old('name')" required />
                <x-input label="Email" name="email" id="email" type="email" :value="old('email')" required />
                <x-input label="Password" name="password" id="password" type="password" required />
                <x-input label="Konfirmasi Password" name="password_confirmation" id="password_confirmation" type="password" required />
                <x-input label="Nomor Telepon" name="number_phone" id="number_phone" :value="old('number_phone')" />
                <x-select label="Role" name="role" id="role" :options="['staff' => 'Staff', 'manager' => 'Manager', 'admin' => 'Admin']" required />
                <x-select label="Toko" name="store_id" id="store_id" :options="$stores->pluck('name', 'id')->toArray()" required />
            </x-form>
        </x-card>
    </div>
</div>
@endsection
