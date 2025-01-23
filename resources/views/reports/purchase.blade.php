@extends('layouts.app')

@section('title', 'Laporan Pembelian')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <livewire:transaction-history :transactionType="'purchase'" />
        <livewire:transaction-detail-panel :transactionType="'purchase'" />
    </div>
</div>
@endsection
