@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <livewire:transaction-history :transactionType="'selling'" />
        <livewire:transaction-detail-panel :transactionType="'selling'" />
    </div>
</div>
@endsection
