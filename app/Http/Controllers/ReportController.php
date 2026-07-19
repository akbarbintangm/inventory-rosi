<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function printReport(Request $request)
    {
        $payload = $this->reportPayload($request);

        return view('reports.print', $payload);
    }

    public function export(Request $request)
    {
        $payload = $this->reportPayload($request);
        $rows = array_merge([$payload['headers']], $payload['rows']);
        $filename = $payload['filename'] . '.xls';

        return response()->streamDownload(function () use ($rows) {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(24);
            $spreadSheet->getActiveSheet()->fromArray($rows);

            $writer = new Xls($spreadSheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);
    }

    private function reportPayload(Request $request): array
    {
        $validatedData = $request->validate([
            'report_type' => 'required|in:products,bahan,orders',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $startDate = $validatedData['start_date'] ?? null;
        $endDate = $validatedData['end_date'] ?? null;

        if ($validatedData['report_type'] === 'products') {
            $headers = ['Nama Barang', 'Kode Barang', 'Stok'];
            $rows = Product::query()
                ->orderBy('name')
                ->get()
                ->map(fn (Product $product) => [
                    $product->name,
                    $product->code,
                    $product->quantity,
                ])
                ->all();

            return [
                'title' => 'Laporan Persediaan Barang',
                'filename' => 'laporan-barang',
                'headers' => $headers,
                'rows' => $rows,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ];
        }

        if ($validatedData['report_type'] === 'bahan') {
            $headers = ['Nama Bahan', 'Kode Bahan', 'Stok'];
            $rows = BahanBaku::query()
                ->orderBy('namabahan')
                ->get()
                ->map(fn (BahanBaku $bahan) => [
                    $bahan->namabahan,
                    $bahan->kodebahan,
                    $bahan->stokbahan,
                ])
                ->all();

            return [
                'title' => 'Laporan Persediaan Bahan',
                'filename' => 'laporan-bahan',
                'headers' => $headers,
                'rows' => $rows,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ];
        }

        $query = Order::query()
            ->with(['customer', 'details.product'])
            ->orderBy('order_date');

        if ($startDate && $endDate) {
            $query->whereBetween('order_date', [$startDate, $endDate]);
        }

        $headers = ['Tanggal', 'Invoice', 'Customer', 'Nama Barang', 'Kode Barang', 'Qty', 'Total'];
        $rows = [];

        foreach ($query->get() as $order) {
            foreach ($order->details as $detail) {
                $rows[] = [
                    optional($order->order_date)->format('Y-m-d'),
                    $order->invoice_no,
                    $order->customer->name ?? '-',
                    $detail->product->name ?? '-',
                    $detail->product->code ?? '-',
                    $detail->quantity,
                    $detail->total,
                ];
            }
        }

        return [
            'title' => 'Laporan Transaksi Customer',
            'filename' => 'laporan-transaksi-customer',
            'headers' => $headers,
            'rows' => $rows,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
}
