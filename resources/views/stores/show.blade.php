@extends('layouts.app')

@section('title', 'Detail Toko')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Detail Toko: {{ $store->name }}</h1>

        <x-card>
            <div class="row">
                <div class="col-md-4">
                    @if($store->url_image)
                        <img src="{{ $store->url_image }}" alt="{{ $store->name }}" class="img-fluid mb-2" style="max-height: 200px;">
                    @else
                        <p>Tidak ada gambar.</p>
                    @endif
                </div>
                <div class="col-md-8">
                    <p><strong>Nama Toko:</strong> {{ $store->name }}</p>
                    <p><strong>Nomor Telepon:</strong> {{ $store->number_phone }}</p>
                    <p><strong>Kode Pos:</strong> {{ $store->postal_code }}</p>
                    <p><strong>Alamat:</strong> {{ $store->address }}</p>
                    <p><strong>Pemilik:</strong> {{ $store->owner->name ?? 'Tidak diketahui' }}</p>  {{-- Assuming you have an owner relationship --}}
                    <form action="{{ route('stores.destroy', $store) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>

                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
