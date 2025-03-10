<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'category_id',
        'picture_url',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function order(): BelongsToMany
    {
        return $this->belongsToMany(Order::class,'item_order', 'item_id', 'order_id');
    }
}
