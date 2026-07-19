<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                {{ __('Data Bahan') }}
            </h2>
        </div>


        @can('raw_material.create')
        <div class="card-actions">
            <x-action.create route="{{ route('bahanbakus.create') }}" />
        </div>
        @endcan

       <div class="card-actions btn-group">
            <div class="dropdown">
                <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <x-icon.vertical-dots />
                </a>
                <div class="dropdown-menu dropdown-menu-end" style="">
                    <a href="#" class="dropdown-item">
                        <x-icon.plus />
                        {{ __('Import Data Bahan') }}
                    </a>
                    <a href="#" class="dropdown-item">
                        <x-icon.plus />
                        {{ __('Export Data Bahan') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                Show
                <div class="mx-2 d-inline-block">
                    <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                    </select>
                </div>
                entries
            </div>
            <div class="ms-auto text-secondary">
                Search:
                <div class="ms-2 d-inline-block">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm"
                        aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner />

    <div class="table-responsive">
        <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
            <thead class="thead-light">
                <tr>
                    <th class="align-middle text-center w-1">
                        {{ __('No.') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('Image') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('namabahan')" href="#" role="button">
                            {{ __('Nama bahan') }}
                            @include('inclues._sort-icon', ['field' => 'namabahan'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('kodebahan')" href="#" role="button">
                            {{ __('Kode Bahan') }}
                            @include('inclues._sort-icon', ['field' => 'kodebahan'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('category_id')" href="#" role="button">
                            {{ __('Kategori') }}
                            @include('inclues._sort-icon', ['field' => 'category_id'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('stokbahan')" href="#" role="button">
                            {{ __('Stok') }}
                            @include('inclues._sort-icon', ['field' => 'stokbahan'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('Action') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bahan_bakus as $bahan)
                    <tr>
                        <td class="align-middle text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="align-middle text-center">
                            <img style="width: 90px;"
                                src="{{ $bahan->fotobahan ? asset('storage/' . $bahan->fotobahan) : asset('assets/img/bahan/default.webp') }}"
                                alt="">
                        </td>
                        <td class="align-middle text-center">
                            {{ $bahan->namabahan }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $bahan->kodebahan }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $bahan->category ? $bahan->category->name : '--' }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $bahan->stokbahan }}
                        </td>
                        <td class="align-middle text-center" style="width: 10%">
                            <x-button.show class="btn-icon" route="{{ route('bahanbakus.show', $bahan->kodebahan) }}" />
                            @can('raw_material.edit')
                            <x-button.edit class="btn-icon" route="{{ route('bahanbakus.edit', $bahan->kodebahan) }}" />
                            @endcan
                            @can('raw_material.delete')
                            <x-button.delete class="btn-icon" route="{{ route('bahanbakus.destroy', $bahan->kodebahan) }}"
                                onclick="return confirm('Are you sure to delete product {{ $bahan->namabahan }} ?')" />
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="align-middle text-center" colspan="7">
                            hasil kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $bahan_bakus->firstItem() }}</span>
            to <span>{{ $bahan_bakus->lastItem() }}</span> of <span>{{ $bahan_bakus->total() }}</span> entries
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $bahan_bakus->links() }}
        </ul>
    </div>
</div>
