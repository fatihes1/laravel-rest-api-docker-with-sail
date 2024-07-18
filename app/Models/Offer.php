<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'discount', 'start_date', 'end_date'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
