<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class BahanBaku extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $fillable = [
        'kodebahan',
        'namabahan',
        'stokbahan',
        'stokperingatan',
        'jenisbahan',
        'detailbahan',
        'tanggalmasuk',
        'hargabeli',
        'fotobahan',
        'category_id',
        'unit_id',
        'create_at',
        'update_at',
        "user_id"
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tanggalmasuk' => 'datetime'
    ];

    public function getRouteKeyName(): string
    {
        return 'kodebahan';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /*protected function buyingPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function sellingPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }*/

    public function scopeSearch($query, $value): void
    {
        $query->where('namabahan', 'like', "%{$value}%")
            ->orWhere('kodebahan', 'like', "%{$value}%");
    }
     /**
     * Get the user that owns the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
