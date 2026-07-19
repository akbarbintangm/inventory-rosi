<?php

namespace App\Http\Controllers\Quotation;

use App\Enums\QuotationStatus;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Http\Requests\Quotation\StoreQuotationRequest;
use App\Services\LowStockNotifier;
use Illuminate\Http\Request;
use Str;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::count();

        return view('quotations.index', [
            'quotations' => $quotations
        ]);
    }

    public function create()
    {
        Cart::instance('quotation')->destroy();

        return view('quotations.create', [
            'cart' => Cart::content('quotation'),
            'products' => Product::all(),
            'customers' => Customer::all(),

            // maybe?
            //'statuses' => QuotationStatus::cases()
        ]);
    }

    public function store(StoreQuotationRequest $request, LowStockNotifier $notifier)
    {
        if (count(Cart::instance('quotation')->content()) === 0) {
            return redirect()->back()->with('message', 'Please search & select products!');
        }
        $lowStockIds = DB::transaction(function () use ($request) {
            $quotation = Quotation::create([
                'date' => $request->date,
                'reference' => $request->reference,
                'customer_id' => $request->customer_id,
                'customer_name' => Customer::findOrFail($request->customer_id)->name,
                'tax_percentage' => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount' => $request->shipping_amount, //* 100,
                'total_amount' => $request->total_amount, //* 100,
                'status' => $request->status,
                'note' => $request->note,
                "uuid" => Str::uuid(),
                "user_id" => auth()->id(),
                'tax_amount' => Cart::instance('quotation')->tax(), //* 100,
                'discount_amount' => Cart::instance('quotation')->discount(), //* 100,
            ]);

            $lowStockIds = [];

            foreach (Cart::instance('quotation')->content() as $cart_item) {
                QuotationDetails::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $cart_item->id,
                    'product_name' => $cart_item->name,
                    'product_code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price, //* 100,
                    'unit_price' => $cart_item->options->unit_price, //* 100,
                    'sub_total' => $cart_item->options->sub_total, //* 100,
                    'product_discount_amount' => $cart_item->options->product_discount, //* 100,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax, //* 100,
                ]);
                //status = sent, reduce product quantity
                if ((int) $request->status === QuotationStatus::SENT->value) {
                    $product = Product::findOrFail($cart_item->id);
                    $product->adjustStock(
                        -((int) $cart_item->qty),
                        'quotation_sent',
                        $quotation,
                        auth()->id(),
                        "Quotation {$quotation->reference}",
                    );

                    if ($product->quantity <= $product->quantity_alert) {
                        $lowStockIds[] = $product->id;
                    }
                }
            }

            Cart::instance('quotation')->destroy();

            return $lowStockIds;
        });

        if ($lowStockIds !== []) {
            $notifier->send(Product::whereIn('id', $lowStockIds)->get());
        }

        return redirect()
            ->route('quotations.index')
            ->with('success', 'Quotation Created!');
    }

    public function show($uuid)
    {
        $quotation = Quotation::where('uuid', $uuid)->firstOrFail();

        return view('quotations.show', [
            'quotation' => $quotation,
            'quotation_details' => QuotationDetails::where('quotation_id', $quotation->id)->get()
        ]);
    }

    public function destroy(Quotation $quotation)
    {
        DB::transaction(function () use ($quotation) {
            $quotation = Quotation::query()->lockForUpdate()->findOrFail($quotation->id);

            if ($quotation->status === QuotationStatus::CANCELED) {
                return;
            }

            if ($quotation->status === QuotationStatus::SENT) {
                $quotation->load('quotationDetails.product');

                foreach ($quotation->quotationDetails as $detail) {
                    $detail->product?->adjustStock(
                        (int) $detail->quantity,
                        'quotation_cancelled',
                        $quotation,
                        auth()->id(),
                        "Pembatalan quotation {$quotation->reference}",
                    );
                }
            }

            $quotation->update(['status' => QuotationStatus::CANCELED]);
        });

        $quotations = Quotation::count();

        return redirect()
            ->route('quotations.index', [
                'quotations' => $quotations
            ]);
    }

    // complete quotaion method
    public function update(Request $request, $uuid, LowStockNotifier $notifier)
    {
        $lowStockIds = DB::transaction(function () use ($uuid) {
            $quotation = Quotation::query()
                ->where('uuid', $uuid)
                ->lockForUpdate()
                ->firstOrFail();

            if ($quotation->status !== QuotationStatus::PENDING) {
                return null;
            }

            $quotation->load('quotationDetails.product');
            $lowStockIds = [];

            foreach ($quotation->quotationDetails as $detail) {
                $product = $detail->product;
                $product->adjustStock(
                    -((int) $detail->quantity),
                    'quotation_sent',
                    $quotation,
                    auth()->id(),
                    "Quotation {$quotation->reference}",
                );

                if ($product->quantity <= $product->quantity_alert) {
                    $lowStockIds[] = $product->id;
                }
            }

            $quotation->update(['status' => QuotationStatus::SENT]);

            return $lowStockIds;
        });

        if ($lowStockIds === null) {
            return redirect()->back()->with('warning', 'Quotation sudah diproses sebelumnya.');
        }

        if ($lowStockIds !== []) {
            $notifier->send(Product::whereIn('id', $lowStockIds)->get());
        }

        return redirect()
            ->route('quotations.index')
            ->with('success', 'Quotation Completed!');
    }
}
