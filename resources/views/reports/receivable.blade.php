@extends('layouts.app')

@section('title', 'Laporan Piutang')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <livewire:debt-history :debtType="'receivable'" />
        <livewire:payment-debt-panel :debtType="'receivable'" />
        <livewire:debt-payment-form :debtType="'receivable'" />
    </div>
</div>
@endsection
