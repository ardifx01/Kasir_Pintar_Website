@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1>Tambah Produk</h1>

        <x-card>
            <x-form action="{{route('products.store')}}" method="POST">
                <x-select label="Toko" name="store_id" id="store_id" :options="$stores->pluck('name', 'id')->toArray()" required />
                <x-select label="Kategori Produk" name="category_product_id" id="category_product_id" :options="$categoryProducts->pluck('name', 'id')->toArray()" required />
                <x-input label="Nama Produk" name="name_product" id="name_product" :value="old('name_product')" required />
                <x-input label="Kode Produk" name="code_product" id="code_product" :value="old('code_product')" required />
                <x-input label="Harga Jual" name="selling_price" id="selling_price" type="number" :value="old('selling_price')" required />
                <x-input label="Harga Beli" name="purchase_price" id="purchase_price" type="number" :value="old('purchase_price')" required />
                <x-input label="Stok" name="stock" id="stock" type="number" :value="old('stock')" required />
                <x-input label="Satuan" name="unit" id="unit" :value="old('unit')" required />
                <x-input-file label="URL Gambar" name="url_image" id="url_image"/>
            </x-form>
        </x-card>
    </div>
</div>
@endsection
