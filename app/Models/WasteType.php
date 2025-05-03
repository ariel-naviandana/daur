<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteType extends Model
{
    protected $fillable = ['category_id', 'name', 'unit', 'price_per_unit', 'image'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
