<?php

namespace App\Services;

use App\Mail\StockAlert;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class LowStockNotifier
{
    public function lowStockProducts(): Collection
    {
        return Product::query()
            ->whereColumn('quantity', '<=', 'quantity_alert')
            ->orderBy('quantity')
            ->get();
    }

    public function send(?Collection $products = null): int
    {
        $products ??= $this->lowStockProducts();

        if ($products->isEmpty()) {
            return 0;
        }

        $recipients = User::role(['owner', 'admin'])
            ->whereNotNull('email')
            ->pluck('email')
            ->unique()
            ->values();

        if ($recipients->isEmpty()) {
            return 0;
        }

        Mail::to($recipients->all())->send(new StockAlert($products->all()));

        return $recipients->count();
    }
}
