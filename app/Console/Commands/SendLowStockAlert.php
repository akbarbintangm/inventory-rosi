<?php

namespace App\Console\Commands;

use App\Services\LowStockNotifier;
use Illuminate\Console\Command;

class SendLowStockAlert extends Command
{
    protected $signature = 'inventory:low-stock-alert {--dry-run : Tampilkan hasil tanpa mengirim email}';

    protected $description = 'Periksa stok produk yang rendah dan kirim email ke owner/admin';

    public function handle(LowStockNotifier $notifier): int
    {
        $products = $notifier->lowStockProducts();

        if ($products->isEmpty()) {
            $this->info('Tidak ada produk dengan stok rendah.');

            return self::SUCCESS;
        }

        $this->table(
            ['Kode', 'Produk', 'Stok', 'Batas'],
            $products->map(fn ($product) => [
                $product->code,
                $product->name,
                $product->quantity,
                $product->quantity_alert,
            ])->all(),
        );

        if ($this->option('dry-run')) {
            $this->info("Dry run: {$products->count()} produk ditemukan.");

            return self::SUCCESS;
        }

        $recipients = $notifier->send($products);
        $this->info("Peringatan dikirim kepada {$recipients} penerima.");

        return self::SUCCESS;
    }
}
