@extends('layouts.app')

@section('title', 'Manajemen Supplier')

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Manajemen Supplier</h1>

        @can('create', App\Models\Supplier::class)
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">Tambah Supplier</a>
        @endcan

        <div class="accordion" id="supplierAccordion">
            @foreach($stores as $store)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $store->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $store->id }}" aria-expanded="false" aria-controls="collapse{{ $store->id }}">
                            Toko {{ $store->name }}
                        </button>
                    </h2>
                    <div id="collapse{{ $store->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $store->id }}" data-bs-parent="#supplierAccordion">
                        <div class="accordion-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>No. Telepon</th>
                                        <th>Alamat</th>
                                        <th>Email</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suppliers->where('store_id', $store->id) as $supplier)
                                        <tr>
                                            <td>{{ $supplier->name }}</td>
                                            <td>{{ $supplier->number_phone }}</td>
                                            <td>{{ $supplier->address }}</td>
                                            <td>{{ $supplier->email }}</td>
                                            <td>
                                                @can('update', $supplier)
                                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm">Ubah</a>
                                                @endcan
                                                @can('delete', $supplier)
                                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">Hapus</button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($suppliers->where('store_id', $store->id)->isEmpty())
                                        <tr><td colspan="5">Tidak ada supplier untuk toko ini.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($stores->isEmpty())
                <p>Tidak ada toko.</p>
            @endif
        </div>
    </div>
</div>
@endsection
