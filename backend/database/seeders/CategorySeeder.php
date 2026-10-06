<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['كاميرات مراقبة' , 'الانتركم' , 'كاميرات الواي فاي', 'انظمة الصوت' , 'كيبل انترنت', 'بدالة التلفون'];

        foreach ($categories as $category) {
            $new_category = new Category();
            $new_category->name_ar = $category;
            $new_category->name_en = $category;
            $new_category->save();
        }
    }
}
