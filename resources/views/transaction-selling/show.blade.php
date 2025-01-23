@extends('layouts.app')

@section('content')
    <x-transaction-detail :transaction="$sellingTransaction" :details="$details" :transactionType="'Penjualan'" />
    <a href="{{ route('transactions.selling') }}" class="btn btn-secondary mt-4">Kembali</a>
    <a href="{{ route('selling-transactions.printPdf', $sellingTransaction) }}" class="btn btn-primary">Cetak Struk</a>
@endsection
