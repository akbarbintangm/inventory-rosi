<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/style.css') }}">
</head>
<body>
    @php
        $user = auth()->user();
    @endphp

    <div class="invoice-16 invoice-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-inner-9" id="invoice_wrapper">
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="logo">
                                        <h1>{{ Str::title($user->store_name ?? config('app.name')) }}</h1>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="invoice">
                                        <h1>Nota # <span>{{ $notaNumber }}</span></h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-info">
                            <div class="row">
                                <div class="col-sm-6 mb-50">
                                    <h4 class="inv-title-1">Jenis Nota</h4>
                                    <p class="inv-from-1">{{ $item['type'] }}</p>
                                    <p class="inv-from-1">{{ $date }}</p>
                                </div>
                                <div class="col-sm-6 text-end mb-50">
                                    <h4 class="inv-title-1">Penanggung Jawab</h4>
                                    <p class="inv-from-1">{{ $pic }}</p>
                                    <p class="inv-from-2">{{ $user->store_address }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="order-summary">
                            <div class="table-outer">
                                <table class="default-table invoice-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Stok Saat Ini</th>
                                            <th class="text-center">Jumlah Terima</th>
                                            <th class="text-center">Satuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{ $item['name'] }}</td>
                                            <td class="text-center">{{ $item['code'] }}</td>
                                            <td class="text-center">{{ $item['stock'] }}</td>
                                            <td class="text-center">{{ $quantity }}</td>
                                            <td class="text-center">{{ $item['unit'] }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <strong>Keterangan:</strong> {{ $notes }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i>
                            Print Nota
                        </a>
                        <a href="{{ route('notaterima.index') }}" class="btn btn-lg btn-download">
                            <i class="fa fa-arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
