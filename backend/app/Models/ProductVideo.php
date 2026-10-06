<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'video_url',
        'video_type',
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'thumbnail_url',
        'sort_order',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns the video.
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
            : ($this->title_en ?? '');
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
     * Extract YouTube video ID from URL.
     */
    public function getYouTubeIdAttribute()
    {
        if ($this->video_type !== 'youtube') {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Extract Vimeo video ID from URL.
     */
    public function getVimeoIdAttribute()
    {
        if ($this->video_type !== 'vimeo') {
            return null;
        }

        preg_match('/vimeo\.com\/(?:.*\/)?(\d+)/', $this->video_url, $matches);
        return $matches[1] ?? null;
    }
}
