<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Transmisi CVT',
                'slug' => 'transmisi-cvt',
                'icon' => 'settings',
                'description' => 'Komponen transmisi CVT seperti pulley, roller, vanbelt',
            ],
            [
                'name' => 'Mesin',
                'slug' => 'mesin',
                'icon' => 'cylinder',
                'description' => 'Blok piston, piston, ring, klep, dan komponen mesin lainnya',
            ],
            [
                'name' => 'Kelistrikan',
                'slug' => 'kelistrikan',
                'icon' => 'zap',
                'description' => 'ECU, CDI, koil, busi, dan sistem kelistrikan',
            ],
            [
                'name' => 'Bahan Bakar',
                'slug' => 'bahan-bakar',
                'icon' => 'fuel',
                'description' => 'Throttle body, karburator, injektor, pompa bensin',
            ],
            [
                'name' => 'Sensor',
                'slug' => 'sensor',
                'icon' => 'cpu',
                'description' => 'Sensor TPS, MAP, IAT, dan sensor lainnya',
            ],
            [
                'name' => 'Paket Bore Up',
                'slug' => 'paket-bore-up',
                'icon' => 'layers',
                'description' => 'Paket lengkap modifikasi bore up',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
