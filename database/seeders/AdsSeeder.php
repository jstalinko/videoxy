<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ad;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

            [
                'title' => 'Adsterra Aling Play',
                'slug' => 'adsterra-aling-play',
                'placement' => 'play',
                'target_url' => 'https://printerkidneyethel.com/aczdrhd2u?key=69818c9464aaa211b4ed47fb49021b13',
                'is_active' => true,
            ],
            [
                'title' => 'Adsterra Aling Upload',
                'slug' => 'adsterra-aling-upload',
                'placement' => 'upload',
                'target_url' => 'https://printerkidneyethel.com/fits1gm8?key=fb115035b51cbc0738ac81724b219666',
                'is_active' => true,
            ],
            [
                'title' => 'Adsterra Kindi Player',
                'slug' => 'adsterra-kindi-play',
                'placement' => 'play',
                'target_url' => 'https://www.effectivegatecpm.com/yihwb3zhuy?key=65a41ad33cc2dc57b137a18ed54a50ad',
                'is_active' => true,
            ],
            [
                'title' => 'Adsterra Kindi Upload',
                'slug' => 'adsterra-kindi-upload',
                'placement' => 'upload',
                'target_url' => 'https://www.effectivegatecpm.com/j1a2jyk66?key=a3b26c38705be6dfa698d17e04ae4091',
                'is_active' => true,
            ]
        ];

        Ad::insert($data);
    }
}
