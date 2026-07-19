@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        @if (!$bahanbakus)
            <x-empty title="Tidak ada data bahan" message="Tambah data bahan terlebih dahulu."
                button_label="{{ __('Tambah bahan') }}" button_route="{{ route('bahanbakus.create') }}"
                permission="raw_material.create" />
        @else
            <div class="container-xl">
                <x-alert />
                @livewire('tables.bahanbaku-table')
            </div>
        @endif
    </div>
@endsection
