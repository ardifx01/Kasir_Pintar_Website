@extends('layouts.app')

@section('title', 'Manajemen Customer')

@section('content')
<div class="row">
    <div class="col-md-3">
        {{-- Sidebar (Anda perlu membuat komponen sidebar) --}}
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Manajemen Customer</h1>

        @can('create', App\Models\Customer::class)
            <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">Tambah Customer</a>
        @endcan


        <div class="accordion" id="customerAccordion">
            @foreach($stores as $store)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $store->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $store->id }}" aria-expanded="false" aria-controls="collapse{{ $store->id }}">
                            Toko {{ $store->name }}
                        </button>
                    </h2>
                    <div id="collapse{{ $store->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $store->id }}" data-bs-parent="#customerAccordion">
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
                                    @foreach($customers->where('store_id', $store->id) as $customer)
                                        <tr>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->number_phone }}</td>
                                            <td>{{ $customer->address }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>
                                                @can('update', $customer)
                                                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm">Ubah</a>
                                                @endcan
                                                @can('delete', $customer)
                                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?')">Hapus</button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($customers->where('store_id', $store->id)->isEmpty())
                                        <tr><td colspan="5">Tidak ada customer untuk toko ini.</td></tr>
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
