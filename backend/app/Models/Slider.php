<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $fillable =[
            'ad_825' ,
            'heading' ,
            'sub_heading',
            'description',

    ];

}
