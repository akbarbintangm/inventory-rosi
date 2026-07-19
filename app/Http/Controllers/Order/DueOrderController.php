<?php

namespace App\Http\Controllers\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Services\LowStockNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DueOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('due', '>', '0')
            ->latest()
            ->with('customer')
            ->get();

        return view('due.index', [
            'orders' => $orders
        ]);
    }

    public function show(Order $order)
    {
        $order->loadMissing(['customer', 'details'])->get();

        return view('due.show', [
           'order' => $order
        ]);
    }

    public function edit(Order $order)
    {
        $order->loadMissing(['customer', 'details'])->get();

        $customers = Customer::select(['id', 'name'])->get();

        return view('due.edit', [
            'order' => $order,
            'customers' => $customers
        ]);
    }

    public function update(Order $order, Request $request, LowStockNotifier $notifier)
    {
        $rules = [
            'pay' => 'required|numeric|min:0.01'
        ];

        $validatedData = $request->validate($rules);

        $lowStockIds = DB::transaction(function () use ($order, $validatedData) {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            $payment = (float) $validatedData['pay'];

            if ($payment > (float) $order->due) {
                throw ValidationException::withMessages([
                    'pay' => 'Pembayaran tidak boleh melebihi sisa tagihan.',
                ]);
            }

            $paidDue = (float) $order->due - $payment;
            $paidPay = (float) $order->pay + $payment;

            $order->update([
                'due' => $paidDue,
                'pay' => $paidPay,
            ]);

            if ($paidDue != 0 || $order->order_status === OrderStatus::COMPLETE) {
                return [];
            }

            $order->load('details.product');
            $lowStockIds = [];

            foreach ($order->details as $detail) {
                $product = $detail->product;
                $product->adjustStock(
                    -((int) $detail->quantity),
                    'order_completed',
                    $order,
                    auth()->id(),
                    "Pelunasan pesanan {$order->invoice_no}",
                );

                if ($product->quantity <= $product->quantity_alert) {
                    $lowStockIds[] = $product->id;
                }
            }

            $order->update(['order_status' => OrderStatus::COMPLETE]);

            return $lowStockIds;
        });

        if ($lowStockIds !== []) {
            $notifier->send(Product::whereIn('id', $lowStockIds)->get());
        }

        return redirect()
            ->route('due.index')
            ->with('success', 'Due amount has been updated!');
    }
}
