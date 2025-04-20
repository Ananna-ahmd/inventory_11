<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceProduct extends Model
{
    protected $guarded = [];
    function product(): BelongsTo{
        return $this->belongsTo(Product::class);}
}
