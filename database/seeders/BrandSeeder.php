<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Proper',
                'slug' => 'proper',
                'description' => 'Brand sparepart motor berkualitas dengan fokus pada performa dan ketahanan.',
            ],
            [
                'name' => 'BRT',
                'slug' => 'brt',
                'description' => 'Spesialis ECU dan sistem kelistrikan motor racing.',
            ],
            [
                'name' => 'MJM',
                'slug' => 'mjm',
                'description' => 'Produk mesin dan piston untuk modifikasi motor.',
            ],
            [
                'name' => 'MJRT',
                'slug' => 'mjrt',
                'description' => 'Brand baru dengan teknologi terkini untuk sparepart motor.',
            ],
            [
                'name' => 'Spectro',
                'slug' => 'spectro',
                'description' => 'Pelumas dan sparepart motor premium.',
            ],
            [
                'name' => 'Yamagata',
                'slug' => 'yamagata',
                'description' => 'Sparepart racing dengan standar kualitas tinggi.',
            ],
            [
                'name' => 'Dr. Pulley',
                'slug' => 'dr-pulley',
                'description' => 'Spesialis pulley dan komponen transmisi CVT.',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
