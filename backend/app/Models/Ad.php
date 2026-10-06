<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    protected $fillable = [
        'ad_1_555',
        'ad_2_555',
        'ad_1_350',
        'ad_2_350',
        'ad_3_350',
        'ad_1_1110',
        'ad_1_shop',
    ];
}
