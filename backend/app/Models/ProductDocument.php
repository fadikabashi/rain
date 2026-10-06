<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'document_type',
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'sort_order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns the document.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the localized title.
     */
    public function getLocalizedTitleAttribute()
    {
        return \Illuminate\Support\Facades\Session::get('locale') === 'ar' && $this->title_ar 
            ? $this->title_ar 
            : $this->title_en;
    }

    /**
     * Get the localized description.
     */
    public function getLocalizedDescriptionAttribute()
    {
        return \Illuminate\Support\Facades\Session::get('locale') === 'ar' && $this->description_ar 
            ? $this->description_ar 
            : ($this->description_en ?? '');
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }
}
