@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Produk: {{ $product->name_product }}</div>

                <div class="card-body">
                    <x-form :action="route('products.update', $product)" :method="'PUT'">
                        <x-select-store :stores="$stores" :selectedStoreId="$product->store_id" />
                        <x-select :label="'Kategori Produk'" :name="'category_product_id'" :options="$categoryProducts->pluck('name','id')" :selected="$product->category_product_id" />
                        <x-input :label="'Nama Produk'" :name="'name_product'" :value="$product->name_product" />
                        <x-input :label="'Kode Produk'" :name="'code_product'" :value="$product->code_product" />
                        <x-input :label="'Harga Jual'" :name="'selling_price'" :type="'number'" :value="$product->selling_price" />
                        <x-input :label="'Harga Beli'" :name="'purchase_price'" :type="'number'" :value="$product->purchase_price" />
                        <x-input :label="'Stok'" :name="'stock'" :type="'number'" :value="$product->stock" />
                        <x-input :label="'Satuan'" :name="'unit'" :value="$product->unit" />
                        <x-input-file :label="'URL Gambar'" :name="'url_image'" :value="$product->url_image" />
                        <x-slot name="submitButtonText">Simpan Perubahan</x-slot>
                    </x-form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
