<?php

namespace Database\Seeders;

use App\Models\Manfacturer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManfacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manfacturers = ['Hikvision' , 'Milesight' , 'Commax', 'Akuvox' , 'Ezviz', 'Ruijie','D-Link' , 'Grandstream','Dsppa', 'Kuwes' , 'Panasoinc'];

        foreach ($manfacturers as $manfacturer) {
            $new_manfacturer = new Manfacturer();
            $new_manfacturer->name_ar = $manfacturer;
            $new_manfacturer->name_en = $manfacturer;
            $new_manfacturer->save();
        }
    }
}
