@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        @if (!$products)
            <x-empty title="Tidak ada data produk" message="Tambah data produk terlebih dahulu."
                button_label="{{ __('Tambah produk') }}" button_route="{{ route('products.create') }}" />

            <div style="text-center" style="padding-top:-25px">
                <center>
                    <a href="{{ route('products.import.view') }}" class="">
                        {{ __('Import Products') }}
                    </a>
                </center>
            </div>
        @else
            <div class="container-xl">
                <x-alert />
                @livewire('tables.product-table')
            </div>
        @endif
    </div>
@endsection
