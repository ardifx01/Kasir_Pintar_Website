@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-6 p-4">
        <div class="flex justify-between mb-3">
            <x-select-store :stores="$stores" :selectedStoreId="$stores->first()?->id" action="transactions.selling"/>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <livewire:product-list :products="$products"/>
        </div>

    </div>
    <div class="col-md-3">
        <livewire:checkout-panel transactionType="purchasing"/>
    </div>
</div>
@endsection
