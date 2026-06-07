@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <x-alert/>

        <div class="row row-cards">
            <form action="{{ route('bahanbakus.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('Foto Bahan') }}
                                </h3>

                                <img class="img-account-profile mb-2" src="{{ asset('assets/img/bahan/default.webp') }}" alt="" id="image-preview" />

                                <div class="small font-italic text-muted mb-2">
                                    JPG atau PNG tidak lebih dari 2 MB
                                </div>

                                <input
                                    type="file"
                                    accept="image/*"
                                    id="image"
                                    name="fotobahan"
                                    class="form-control @error('fotobahan') is-invalid @enderror"
                                    onchange="previewImage();"
                                >

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
                            <div class="card-header">
                                <div>
                                    <h3 class="card-title">
                                        {{ __('Tambah Bahan') }}
                                    </h3>
                                </div>

                                <div class="card-actions">
                                    <a href="{{ route('bahanbakus.index') }}" class="btn-action">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-cards">
                                    <div class="col-md-12">
                                        <x-input name="namabahan"
                                                label="Nama Bahan"
                                                 id="namabahan"
                                                 placeholder="Masukkan nama bahan"
                                                 value="{{ old('namabahan') }}"
                                        />
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">
                                                Kategori Bahan
                                                <span class="text-danger">*</span>
                                            </label>

                                            @if ($categories->count() === 1)
                                                <select name="category_id" id="category_id"
                                                        class="form-select @error('category_id') is-invalid @enderror"
                                                        readonly
                                                >
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" selected>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="category_id" id="category_id"
                                                        class="form-select @error('category_id') is-invalid @enderror"
                                                >
                                                    <option selected="" disabled="">
                                                        Pilih kategori:
                                                    </option>

                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected="selected" @endif>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif

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

                                            @if ($units->count() === 1)
                                                <select name="unit_id" id="unit_id"
                                                        class="form-select @error('unit_id') is-invalid @enderror"
                                                        readonly
                                                >
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}" selected>
                                                            {{ $unit->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="unit_id" id="unit_id"
                                                        class="form-select @error('unit_id') is-invalid @enderror"
                                                >
                                                    <option selected="" disabled="">
                                                        Pilih unit:
                                                    </option>

                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}" @if(old('unit_id') == $unit->id) selected="selected" @endif>{{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif

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
                                                 value="{{ old('hargabeli') }}"
                                        />
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <x-input type="number"
                                                 label="Stok Bahan"
                                                 name="stokbahan"
                                                 id="stokbahan"
                                                 placeholder="0"
                                                 value="{{ old('stokbahan') }}"
                                        />
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <x-input type="text"
                                                 label="Jenis bahan"
                                                 name="jenisbahan"
                                                 id="jenisbahan"
                                                 placeholder="Masukkan jenis bahan"
                                                 value="{{ old('jenisbahan') }}"
                                        />
                                    </div>

                                    <div class="col-md-12">
                                        <x-input type="date"
                                                name="tanggalmasuk"
                                                label="Tanggal Masuk"
                                                id="tanggalmasuk"
                                                placeholder="Masukkan tanggal bahan datang"
                                                value="{{ old('tanggalmasuk') }}"
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
                                                      placeholder=" Detail bahan"
                                            ></textarea>

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
                                <x-button.save type="submit">
                                    {{ __('Save') }}
                                </x-button.save>

                                <a class="btn btn-warning" href="{{ url()->previous() }}">
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
