<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'spec_key_en',
        'spec_key_ar',
        'spec_value_en',
        'spec_value_ar',
        'sort_order',
        'group',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns the specification.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the localized spec key.
     */
    public function getLocalizedKeyAttribute()
    {
        return \Illuminate\Support\Facades\Session::get('locale') === 'ar' && $this->spec_key_ar 
            ? $this->spec_key_ar 
            : $this->spec_key_en;
    }

    /**
     * Get the localized spec value.
     */
    public function getLocalizedValueAttribute()
    {
        return \Illuminate\Support\Facades\Session::get('locale') === 'ar' && $this->spec_value_ar 
            ? $this->spec_value_ar 
            : $this->spec_value_en;
    }
}
