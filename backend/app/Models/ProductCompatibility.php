<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCompatibility extends Model
{
    use HasFactory;

    protected $table = 'product_compatibility';

    protected $fillable = [
        'product_id',
        'compatible_product_id',
        'compatibility_type',
        'notes',
        'sort_order',
    ];

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the compatible product.
     */
    public function compatibleProduct()
    {
        return $this->belongsTo(Product::class, 'compatible_product_id');
    }

    /**
     * Scope for required compatibility.
     */
    public function scopeRequired($query)
    {
        return $query->where('compatibility_type', 'required');
    }

    /**
     * Scope for recommended compatibility.
     */
    public function scopeRecommended($query)
    {
        return $query->where('compatibility_type', 'recommended');
    }
}
