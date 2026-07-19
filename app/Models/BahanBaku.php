<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


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

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }

    public function adjustStock(
        int $quantityChange,
        string $mutationType,
        ?EloquentModel $reference = null,
        ?int $userId = null,
        ?string $note = null,
    ): self {
        if ($quantityChange === 0) {
            return $this;
        }

        return DB::transaction(function () use ($quantityChange, $mutationType, $reference, $userId, $note) {
            $material = self::query()->lockForUpdate()->findOrFail($this->getKey());
            $before = (int) $material->stokbahan;
            $after = $before + $quantityChange;

            if ($after < 0) {
                throw ValidationException::withMessages([
                    'stock' => "Stok bahan baku {$material->namabahan} tidak mencukupi.",
                ]);
            }

            $material->forceFill(['stokbahan' => $after])->save();
            $material->stockMutations()->create([
                'quantity_before' => $before,
                'quantity_change' => $quantityChange,
                'quantity_after' => $after,
                'mutation_type' => $mutationType,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->getKey(),
                'note' => $note,
                'created_by' => $userId ?? Auth::id(),
            ]);

            $this->setRawAttributes($material->getAttributes(), true);

            return $this;
        });
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
