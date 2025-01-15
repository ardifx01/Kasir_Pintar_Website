@extends('layouts.app')

@section('title', 'Manajemen Staff')

@section('content')
<div class="row">
    <div class="col-md-3">
        {{-- Sidebar (Anda perlu membuat komponen sidebar) --}}
        <x-sidebar role="{{ $role }}" />
    </div>
    <div class="col-md-9 p-4">
        <h1>Manajemen Staff</h1>
        @can('create', App\Models\Staff::class)
            <a href="{{ route('staffs.create') }}" class="btn btn-primary mb-3">Tambah Staff</a>
        @endcan

        <div class="accordion" id="staffAccordion">
            @foreach($stores as $store)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $store->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $store->id }}" aria-expanded="false" aria-controls="collapse{{ $store->id }}">
                            Toko {{ $store->name }}
                        </button>
                    </h2>
                    <div id="collapse{{ $store->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $store->id }}" data-bs-parent="#staffAccordion">
                        <div class="accordion-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($staffs->where('store_id', $store->id) as $staff)
                                        <tr>
                                            <td>{{ $staff->user->name }}</td>
                                            <td>{{ $staff->user->email }}</td>
                                            <td>{{ $staff->role }}</td>
                                            <td>
                                                @can('delete', $staff)
                                                    <form action="{{ route('staffs.destroy', $staff) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus staff ini?')">Hapus</button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($staffs->where('store_id', $store->id)->isEmpty())
                                        <tr><td colspan="4">Tidak ada staff untuk toko ini.</td></tr>
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
