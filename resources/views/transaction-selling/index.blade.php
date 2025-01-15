@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('content')
<div class="flex">
    <div class="w-2/3 p-4">
        @if ($stores->count() > 0)
            <x-select-store :stores="$stores" />
            <livewire:product-list :products="$products" />
        @else
            <div class="p-4 bg-yellow-100 border border-yellow-400 rounded text-center">
                <p>Anda belum memiliki toko atau belum terdaftar sebagai staff. Silahkan hubungi admin.</p>
            </div>
        @endif
    </div>
    <div class="w-1/3 p-4">
        <livewire:checkout-panel />
    </div>
</div>
@endsection
