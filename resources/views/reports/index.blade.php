@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <x-alert/>

        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Cetak Laporan') }}
                    </h3>
                </div>
            </div>

            <form method="POST">
                @csrf

                <div class="card-body">
                    <div class="row row-cards">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="report_type" class="form-label">
                                    {{ __('Jenis Laporan') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="report_type" id="report_type" class="form-select @error('report_type') is-invalid @enderror">
                                    <option value="products" @selected(old('report_type') === 'products')>
                                        {{ __('Persediaan Barang') }}
                                    </option>
                                    <option value="bahan" @selected(old('report_type') === 'bahan')>
                                        {{ __('Persediaan Bahan') }}
                                    </option>
                                    <option value="orders" @selected(old('report_type') === 'orders')>
                                        {{ __('Transaksi Customer') }}
                                    </option>
                                </select>
                                @error('report_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <x-input type="date"
                                label="Tanggal Awal"
                                name="start_date"
                                id="start_date"
                                value="{{ old('start_date') }}"
                            />
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <x-input type="date"
                                label="Tanggal Akhir"
                                name="end_date"
                                id="end_date"
                                value="{{ old('end_date') }}"
                            />
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary" formaction="{{ route('reports.print') }}">
                        {{ __('Cetak') }}
                    </button>
                    <button type="submit" class="btn btn-success" formaction="{{ route('reports.export') }}">
                        {{ __('Export Excel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
