@extends('layouts.app')

@section('title', 'Laporan Hutang')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <livewire:debt-history :debtType="'payable'" />
        <livewire:payment-debt-panel :debtType="'payable'" />
        <livewire:debt-payment-form :debtType="'payable'" />
    </div>
</div>
@endsection
