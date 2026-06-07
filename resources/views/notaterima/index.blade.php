@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <x-alert/>

        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Nota Terima') }}
                    </h3>
                </div>
            </div>

            <form action="{{ route('notaterima.print') }}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="row row-cards">
                        <div class="col-sm-6 col-md-4">
                            <div class="mb-3">
                                <label for="type" class="form-label">
                                    {{ __('Jenis Data') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                                    <option value="product" @selected(old('type') === 'product')>
                                        {{ __('Barang Jadi') }}
                                    </option>
                                    <option value="bahan" @selected(old('type') === 'bahan')>
                                        {{ __('Bahan Baku') }}
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-8" id="product_select">
                            <div class="mb-3">
                                <label for="product_id" class="form-label">
                                    {{ __('Barang') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror">
                                    <option selected="" disabled="">{{ __('Pilih barang:') }}</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                            {{ $product->name }} - {{ $product->code }} - {{ __('Stok') }} {{ $product->quantity }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-8" id="bahan_select">
                            <div class="mb-3">
                                <label for="bahan_baku_id" class="form-label">
                                    {{ __('Bahan') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="bahan_baku_id" id="bahan_baku_id" class="form-select @error('bahan_baku_id') is-invalid @enderror">
                                    <option selected="" disabled="">{{ __('Pilih bahan:') }}</option>
                                    @foreach ($bahanbakus as $bahan)
                                        <option value="{{ $bahan->id }}" @selected(old('bahan_baku_id') == $bahan->id)>
                                            {{ $bahan->namabahan }} - {{ $bahan->kodebahan }} - {{ __('Stok') }} {{ $bahan->stokbahan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bahan_baku_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <x-input type="number"
                                label="Jumlah Diterima"
                                name="quantity"
                                id="quantity"
                                placeholder="0"
                                value="{{ old('quantity', 1) }}"
                            />
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <x-input type="date"
                                label="Tanggal Terima"
                                name="date"
                                id="date"
                                value="{{ old('date', now()->format('Y-m-d')) }}"
                            />
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <x-input type="text"
                                label="Penanggung Jawab"
                                name="pic"
                                id="pic"
                                value="{{ old('pic', auth()->user()->name) }}"
                            />
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">
                                    {{ __('Keterangan') }}
                                </label>
                                <textarea name="notes" id="notes" rows="4"
                                    class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="Keterangan nota">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Cetak Nota') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@pushonce('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const type = document.getElementById('type');
        const productSelect = document.getElementById('product_select');
        const bahanSelect = document.getElementById('bahan_select');
        const productInput = document.getElementById('product_id');
        const bahanInput = document.getElementById('bahan_baku_id');

        function toggleItemSelect() {
            const isBahan = type.value === 'bahan';

            productSelect.classList.toggle('d-none', isBahan);
            bahanSelect.classList.toggle('d-none', !isBahan);
            productInput.disabled = isBahan;
            bahanInput.disabled = !isBahan;
        }

        type.addEventListener('change', toggleItemSelect);
        toggleItemSelect();
    });
</script>
@endpushonce
