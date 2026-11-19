<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'description', 'price', 'image', 'is_available'];

    protected function casts(): array
    {
        return ['is_available' => 'boolean', 'price' => 'integer'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function imageUrl(): string
    {
        $defaultImage = config('menu-images.'.$this->name, 'images/meal.svg');

        return $this->image ? route('menu.image', $this) : asset($defaultImage);
    }
}
