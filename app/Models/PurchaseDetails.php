<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseDetails extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'purchase_id',
        'product_id',
        'bahan_baku_id',
        'item_type',
        'quantity',
        'unitcost',
        'total',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $with = ['product', 'bahanBaku'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function getItemNameAttribute(): string
    {
        return $this->bahanBaku->namabahan ?? $this->product->name ?? '-';
    }

    public function getItemCodeAttribute(): string
    {
        return $this->bahanBaku->kodebahan ?? $this->product->code ?? '-';
    }

    public function getItemStockAttribute(): int
    {
        return $this->bahanBaku->stokbahan ?? $this->product->quantity ?? 0;
    }

    public function getItemImageAttribute(): ?string
    {
        return $this->bahanBaku->fotobahan ?? $this->product->product_image ?? null;
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}
