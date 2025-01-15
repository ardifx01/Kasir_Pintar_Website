@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Daftar Toko</h1>
        @can('create', App\Models\Store::class)
            <a href="{{ route('stores.create') }}" class="btn btn-primary">Buat Toko Baru</a>
        @endcan
        <div class="mt-4 row d-flex gap-2">
            @forelse ($stores as $store)
                <div class="col-md-4 mb-4">
                    <x-card-store :store="$store" />
                </div>
            @empty
                <p>Tidak ada toko.</p>
            @endforelse
        </div>
    </div>
</div>
</section>
@endsection
