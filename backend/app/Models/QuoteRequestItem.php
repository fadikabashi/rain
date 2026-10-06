<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class QuoteRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_request_batch_id',
        'product_id',
        'quantity',
        'line_note',
        'quoted_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quoted_price' => 'decimal:2',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(QuoteRequestBatch::class, 'quote_request_batch_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
