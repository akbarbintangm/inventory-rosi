@extends('layouts.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center mb-3">
                <div class="col">
                    <h2 class="page-title">
                        {{ __('Edit Bahan') }}
                    </h2>
                </div>
            </div>

            @include('partials._breadcrumbs', ['model' => $bahan])
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <form action="{{ route('bahanbakus.update', $bahan->kodebahan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        {{ __('Foto Bahan') }}
                                    </h3>

                                    <img class="img-account-profile mb-2"
                                        src="{{ $bahan->fotobahan ? asset('storage/' . $bahan->fotobahan) : asset('assets/img/bahan/default.webp') }}"
                                        alt="" id="image-preview">

                                    <div class="small font-italic text-muted mb-2">
                                        JPG atau PNG tidak lebih dari 2 MB
                                    </div>

                                    <input type="file" accept="image/*" id="image" name="fotobahan"
                                        class="form-control @error('fotobahan') is-invalid @enderror"
                                        onchange="previewImage();">

                                    @error('fotobahan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        {{ __('Detail Bahan') }}
                                    </h3>

                                    <div class="row row-cards">
                                        <div class="col-md-12">
                                            <x-input name="namabahan"
                                                label="Nama Bahan"
                                                id="namabahan"
                                                placeholder="Masukkan nama bahan"
                                                value="{{ old('namabahan', $bahan->namabahan) }}"
                                            />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">
                                                    Kategori Bahan
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select name="category_id" id="category_id"
                                                    class="form-select @error('category_id') is-invalid @enderror">
                                                    <option selected="" disabled="">Pilih kategori:</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            @if(old('category_id', $bahan->category_id) == $category->id) selected="selected" @endif>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('category_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="unit_id">
                                                    {{ __('Satuan') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select name="unit_id" id="unit_id"
                                                    class="form-select @error('unit_id') is-invalid @enderror">
                                                    <option selected="" disabled="">Pilih unit:</option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}"
                                                            @if(old('unit_id', $bahan->unit_id) == $unit->id) selected="selected" @endif>
                                                            {{ $unit->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('unit_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input type="number"
                                                label="Harga Bahan"
                                                name="hargabeli"
                                                id="hargabeli"
                                                placeholder="0"
                                                value="{{ old('hargabeli', $bahan->hargabeli) }}"
                                            />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input type="number"
                                                label="Stok Bahan"
                                                name="stokbahan"
                                                id="stokbahan"
                                                placeholder="0"
                                                value="{{ old('stokbahan', $bahan->stokbahan) }}"
                                            />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input type="text"
                                                label="Jenis bahan"
                                                name="jenisbahan"
                                                id="jenisbahan"
                                                placeholder="Masukkan jenis bahan"
                                                value="{{ old('jenisbahan', $bahan->jenisbahan) }}"
                                            />
                                        </div>

                                        <div class="col-md-12">
                                            <x-input type="date"
                                                name="tanggalmasuk"
                                                label="Tanggal Masuk"
                                                id="tanggalmasuk"
                                                placeholder="Masukkan tanggal bahan datang"
                                                value="{{ old('tanggalmasuk', optional($bahan->tanggalmasuk)->format('Y-m-d')) }}"
                                            />
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="detailbahan" class="form-label">
                                                    {{ __('Detail Bahan') }}
                                                </label>

                                                <textarea name="detailbahan"
                                                    id="detailbahan"
                                                    rows="5"
                                                    class="form-control @error('detailbahan') is-invalid @enderror"
                                                    placeholder="Detail bahan">{{ old('detailbahan', $bahan->detailbahan) }}</textarea>

                                                @error('detailbahan')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-end">
                                    <button class="btn btn-primary" type="submit">
                                        {{ __('Update') }}
                                    </button>

                                    <a class="btn btn-danger" href="{{ route('bahanbakus.index') }}">
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@pushonce('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpushonce
