<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = ['Menunggu', 'Diproses', 'Selesai', 'Dibatalkan'];

    protected $fillable = ['user_id', 'number', 'customer_name', 'phone', 'address', 'event_date', 'notes', 'total', 'status'];

    protected function casts(): array
    {
        return ['event_date' => 'date', 'total' => 'integer'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
