<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Product;
use Illuminate\Http\Request;

class NotaTerimaController extends Controller
{
    public function index()
    {
        return view('notaterima.index', [
            'products' => Product::query()
                ->with(['category', 'unit'])
                ->orderBy('name')
                ->get(),
            'bahanbakus' => BahanBaku::query()
                ->with(['category', 'unit'])
                ->orderBy('namabahan')
                ->get(),
        ]);
    }

    public function printNota(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:product,bahan',
            'product_id' => 'required_if:type,product|nullable|integer',
            'bahan_baku_id' => 'required_if:type,bahan|nullable|integer',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'pic' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validatedData['type'] === 'product') {
            $product = Product::query()
                ->with('unit')
                ->findOrFail($validatedData['product_id']);

            $item = [
                'type' => 'Barang Jadi',
                'name' => $product->name,
                'code' => $product->code,
                'stock' => $product->quantity,
                'unit' => $product->unit->name ?? '-',
            ];
        } else {
            $bahan = BahanBaku::query()
                ->with('unit')
                ->findOrFail($validatedData['bahan_baku_id']);

            $item = [
                'type' => 'Bahan Baku',
                'name' => $bahan->namabahan,
                'code' => $bahan->kodebahan,
                'stock' => $bahan->stokbahan,
                'unit' => $bahan->unit->name ?? '-',
            ];
        }

        return view('notaterima.print', [
            'item' => $item,
            'quantity' => $validatedData['quantity'],
            'date' => $validatedData['date'],
            'pic' => $validatedData['pic'],
            'notes' => $validatedData['notes'] ?? '-',
            'notaNumber' => 'NT-' . now()->format('YmdHis'),
        ]);
    }
}
