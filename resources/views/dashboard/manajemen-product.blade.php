@extends('layouts.app')

@section('title', 'Login')

@section('content')
        <div class="row">
            <div class="col-md-3">
                <x-sidebar role="{{ $role }}" />
            </div>
            <div class="col-md-9 p-4 flex">
                <x-card-menu
                    routeName="products.index"
                    img="products.png"
                    label="Manajemen Product"
                />

            </div>
        </div>
@endsection
