@extends('layouts.app')

@section('content')
    <x-transaction-detail :transaction="$purchaseTransaction" :details="$details" :transactionType="'Penjualan'" />
    <a href="{{ route('transactions.purchasing') }}" class="btn btn-secondary mt-4">Kembali</a>
    <a href="{{ route('purchase-transactions.printPdf', $purchaseTransaction) }}" class="btn btn-primary">Cetak Struk</a>
@endsection
