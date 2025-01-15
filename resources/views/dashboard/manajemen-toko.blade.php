@extends('layouts.app')

@section('title', 'manajemen toko')

@section('content')
        <div class="row">
            <div class="col-md-3">
                <x-sidebar role="{{ $role }}" />
            </div>
            <div class="col-md-9 p-4 flex">
                <x-card-menu
                    routeName="stores.index"
                    img="store.png"
                    label="Manajemen Store"
                />
                <x-card-menu
                    routeName="staffs.index"
                    img="staff.png"
                    label="Manajemen Staff"
                />
            </div>
        </div>
@endsection
