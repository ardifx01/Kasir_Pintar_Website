@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Manajemen Produk</h1>

        <div class="flex justify-between mb-3">
            <x-select-store :stores="$stores" :selectedStoreId="$stores->first()?->id" action="products.index"/>
            @can('create', App\Models\Product::class)
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    @svg('ri-add-circle-line','w-20 me-2')
                    Tambah Produk
                </a>
            @endcan
        </div>

        <div class="row mt-4 d-flex flex-wrap gap-4">
            @foreach($products as $product)
                <x-card-product :product="$product" />
            @endforeach
            @if ($products->isEmpty())
                <div class="col-md-12">
                    <p class="text-center">Tidak ada produk.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
