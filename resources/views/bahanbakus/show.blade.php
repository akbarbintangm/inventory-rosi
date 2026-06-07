@extends('layouts.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center mb-3">
                <div class="col">
                    <h2 class="page-title">
                        {{ $bahan->namabahan }}
                    </h2>
                </div>
            </div>

            @include('partials._breadcrumbs')
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('Foto Bahan') }}
                                </h3>

                                <img style="width: 90px;" id="image-preview"
                                    src="{{ $bahan->fotobahan ? asset('storage/' . $bahan->fotobahan) : asset('assets/img/bahan/default.webp') }}"
                                    alt="" class="img-account-profile mb-2">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ __('Detail Bahan') }}
                                </h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                                    <tbody>
                                        <tr>
                                            <td>Nama Bahan</td>
                                            <td>{{ $bahan->namabahan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kode Bahan</td>
                                            <td>{{ $bahan->kodebahan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Barcode</td>
                                            <td>{!! $barcode !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Kategori</td>
                                            <td>{{ $bahan->category ? $bahan->category->name : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Satuan</td>
                                            <td>{{ $bahan->unit ? $bahan->unit->name : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Stok</td>
                                            <td>{{ $bahan->stokbahan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Bahan</td>
                                            <td>{{ $bahan->jenisbahan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Masuk</td>
                                            <td>{{ optional($bahan->tanggalmasuk)->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Harga Beli</td>
                                            <td>{{ $bahan->hargabeli }}</td>
                                        </tr>
                                        <tr>
                                            <td>Detail Bahan</td>
                                            <td>{{ $bahan->detailbahan }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-end">
                                <a class="btn btn-info" href="{{ route('bahanbakus.index') }}">
                                    {{ __('Back') }}
                                </a>
                                <a class="btn btn-warning" href="{{ route('bahanbakus.edit', $bahan->kodebahan) }}">
                                    {{ __('Edit') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
