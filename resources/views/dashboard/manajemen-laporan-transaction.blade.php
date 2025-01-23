@extends('layouts.app')

@section('title', 'manajemen laporan transaction')

@section('content')
        <div class="row">
            <div class="col-md-3">
                <x-sidebar role="{{ $role }}" />
            </div>

            <div class="col-md-9 p-4 w-1600  gap-2 d-flex flex-wrap">
                <x-card-menu
                    routeName="report.selling"
                    img="selling-report.png"
                    label="Laporan Transaksi Penjualan"
                />
                <x-card-menu
                    routeName="report.purchase"
                    img="purchase-report.png"
                    label="Laporan Transaksi Pembelian"
                />
                <x-card-menu
                    routeName="report.payable"
                    img="payable-report.png"
                    label="Laporan Hutang"
                />
                <x-card-menu
                    routeName="report.receivable"
                    img="receivable-report.png"
                    label="Laporan Piutang"
                />
            </div>

        </div>
@endsection
