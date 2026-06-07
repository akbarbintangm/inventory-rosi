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
    <div class="invoice-16 invoice-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-inner-9" id="invoice_wrapper">
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="logo">
                                        <h1>{{ $title }}</h1>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="invoice">
                                        <h1>{{ now()->format('Y-m-d') }}</h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($startDate && $endDate)
                            <div class="invoice-info">
                                <div class="row">
                                    <div class="col-sm-12 mb-50">
                                        <h4 class="inv-title-1">Periode</h4>
                                        <p class="inv-from-1">{{ $startDate }} sampai {{ $endDate }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="order-summary">
                            <div class="table-outer">
                                <table class="default-table invoice-table">
                                    <thead>
                                        <tr>
                                            @foreach ($headers as $header)
                                                <th class="text-center">{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($rows as $row)
                                            <tr>
                                                @foreach ($row as $column)
                                                    <td class="text-center">{{ $column }}</td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="{{ count($headers) }}">
                                                    {{ __('Data tidak ditemukan') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i>
                            Print Laporan
                        </a>
                        <a href="{{ route('reports.index') }}" class="btn btn-lg btn-download">
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
