@extends('layouts.app')

@section('title', 'manajemen toko')

@section('content')
        <div class="row">
            <div class="col-md-3">
                <x-sidebar role="{{ $role }}" />
            </div>
            <div class="col-md-9 p-4 flex">
                <x-card-menu
                    routeName="transaction-selling"
                    img="trade.png"
                    label="Transaksi Penjualan"
                />
                <x-card-menu
                    routeName="staffs.index"
                    img="receiver.png"
                    label="Transaksi Pembelian"
                />
            </div>
        </div>
@endsection
