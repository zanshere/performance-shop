<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Pulley Custom
            [
                'name' => 'Pulley Custom 13.5 Derajat',
                'slug' => 'pulley-custom-13-5-derajat',
                'description' => 'Pulley custom dengan sudut 13.5 derajat untuk akselerasi lebih responsif. Material aluminium berkualitas dengan coating khusus untuk ketahanan tinggi.',
                'specifications' => json_encode([
                    'Material' => 'Aluminium 6061',
                    'Sudut' => '13.5°',
                    'Diameter' => '120mm',
                    'Berat' => '850 gram',
                    'Warna' => 'Silver Anodized',
                    'Garansi' => '6 bulan'
                ]),
                'price' => 450000,
                'discount_price' => 425000,
                'stock' => 25,
                'sku' => 'PUL-135-PRO',
                'brand' => 'Proper',
                'category' => 'Transmisi CVT',
                'images' => json_encode(['pulley-1.jpg', 'pulley-2.jpg']),
                'is_featured' => true,
                'weight' => 0.85,
                'compatibility' => json_encode(['Honda Beat', 'Yamaha Mio', 'Suzuki Nex'])
            ],
            [
                'name' => 'Pulley Custom 13.8 Derajat',
                'slug' => 'pulley-custom-13-8-derajat',
                'description' => 'Pulley dengan sudut 13.8 derajat, kombinasi optimal antara akselerasi dan top speed. Cocok untuk harian dan touring.',
                'specifications' => json_encode([
                    'Material' => 'Aluminium 7075',
                    'Sudut' => '13.8°',
                    'Diameter' => '125mm',
                    'Berat' => '880 gram',
                    'Warna' => 'Black Anodized',
                    'Garansi' => '6 bulan'
                ]),
                'price' => 480000,
                'stock' => 18,
                'sku' => 'PUL-138-BRT',
                'brand' => 'BRT',
                'category' => 'Transmisi CVT',
                'images' => json_encode(['pulley-138-1.jpg']),
                'is_featured' => false,
                'weight' => 0.88,
                'compatibility' => json_encode(['Honda Vario', 'Yamaha NMAX', 'Honda PCX'])
            ],
            [
                'name' => 'Pulley Custom 14 Derajat',
                'slug' => 'pulley-custom-14-derajat',
                'description' => 'Pulley racing dengan sudut 14 derajat untuk top speed maksimal. Design khusus untuk balap dan performance extreme.',
                'specifications' => json_encode([
                    'Material' => 'Titanium Grade 2',
                    'Sudut' => '14°',
                    'Diameter' => '130mm',
                    'Berat' => '750 gram',
                    'Warna' => 'Gold Anodized',
                    'Garansi' => '1 tahun'
                ]),
                'price' => 650000,
                'discount_price' => 595000,
                'stock' => 12,
                'sku' => 'PUL-140-DRP',
                'brand' => 'Dr. Pulley',
                'category' => 'Transmisi CVT',
                'images' => json_encode(['pulley-14-1.jpg', 'pulley-14-2.jpg']),
                'is_featured' => true,
                'weight' => 0.75,
                'compatibility' => json_encode(['Yamaha R15', 'Honda CBR150R', 'Kawasaki Ninja 150'])
            ],

            // Rumah Roller
            [
                'name' => 'Rumah Roller Racing',
                'slug' => 'rumah-roller-racing',
                'description' => 'Rumah roller racing dengan material khusus untuk mengurangi gesekan dan meningkatkan respons CVT.',
                'specifications' => json_encode([
                    'Material' => 'Steel Chrome',
                    'Kapasitas' => '6 roller',
                    'Berat' => '320 gram',
                    'Finish' => 'Chrome Plating',
                    'Garansi' => '3 bulan'
                ]),
                'price' => 185000,
                'stock' => 30,
                'sku' => 'RR-01-MJM',
                'brand' => 'MJM',
                'category' => 'Transmisi CVT',
                'images' => json_encode(['rumah-roller-1.jpg']),
                'is_featured' => false,
                'weight' => 0.32,
                'compatibility' => json_encode(['All Honda Matic', 'All Yamaha Matic'])
            ],

            // Roller
            [
                'name' => 'Roller Sliding Weight 10gr',
                'slug' => 'roller-sliding-weight-10gr',
                'description' => 'Roller sliding weight 10 gram untuk transmisi yang lebih halus dan responsif. Set isi 6 pcs.',
                'specifications' => json_encode([
                    'Berat per piece' => '10 gram',
                    'Jumlah per set' => '6 pcs',
                    'Material' => 'Polyurethane',
                    'Warna' => 'Hitam',
                    'Garansi' => '1 bulan'
                ]),
                'price' => 95000,
                'stock' => 50,
                'sku' => 'ROL-10-YMG',
                'brand' => 'Yamagata',
                'category' => 'Transmisi CVT',
                'images' => json_encode(['roller-10g-1.jpg']),
                'is_featured' => false,
                'weight' => 0.06,
                'compatibility' => json_encode(['Semua tipe matic'])
            ],

            // ECU
            [
                'name' => 'ECU Racing Dual Map',
                'slug' => 'ecu-racing-dual-map',
                'description' => 'ECU racing dengan dual map (street & race) untuk performa maksimal. Plug and play installation.',
                'specifications' => json_encode([
                    'Tipe' => 'Dual Map Programmable',
                    'Konektor' => 'Original Plug',
                    'Program' => '2 Map (Street/Race)',
                    'Garansi' => '1 tahun',
                    'Installasi' => 'Plug & Play'
                ]),
                'price' => 2500000,
                'discount_price' => 2350000,
                'stock' => 8,
                'sku' => 'ECU-DUAL-BRT',
                'brand' => 'BRT',
                'category' => 'Kelistrikan',
                'images' => json_encode(['ecu-1.jpg', 'ecu-2.jpg']),
                'is_featured' => true,
                'weight' => 0.45,
                'compatibility' => json_encode(['Honda Beat FI', 'Honda Vario FI', 'Yamaha NMAX'])
            ],

            // Throttle Body
            [
                'name' => 'TB Downdraft 28mm',
                'slug' => 'tb-downdraft-28mm',
                'description' => 'Throttle body downdraft 28mm dengan butterfly valve racing untuk aliran udara maksimal.',
                'specifications' => json_encode([
                    'Diameter' => '28mm',
                    'Tipe' => 'Downdraft',
                    'Material' => 'Aluminium CNC',
                    'Warna' => 'Silver',
                    'Garansi' => '6 bulan'
                ]),
                'price' => 1800000,
                'stock' => 6,
                'sku' => 'TB-28-SPC',
                'brand' => 'Spectro',
                'category' => 'Bahan Bakar',
                'images' => json_encode(['tb-28-1.jpg']),
                'is_featured' => true,
                'weight' => 0.85,
                'compatibility' => json_encode(['Honda CBR150R', 'Yamaha R15', 'Kawasaki Ninja 150'])
            ],

            // Blok Piston
            [
                'name' => 'Blok Piston 62mm Racing',
                'slug' => 'blok-piston-62mm-racing',
                'description' => 'Blok piston racing 62mm dengan nikasil coating untuk ketahanan dan performa tinggi.',
                'specifications' => json_encode([
                    'Diameter' => '62mm',
                    'Material' => 'Aluminium + Nikasil',
                    'Tinggi' => '65mm',
                    'Garansi' => '6 bulan'
                ]),
                'price' => 1200000,
                'discount_price' => 1100000,
                'stock' => 10,
                'sku' => 'BP-62-MJM',
                'brand' => 'MJM',
                'category' => 'Mesin',
                'images' => json_encode(['blok-62-1.jpg']),
                'is_featured' => true,
                'weight' => 1.2,
                'compatibility' => json_encode(['Honda Beat', 'Honda Vario', 'Yamaha Mio'])
            ],

            // Sensor TPS
            [
                'name' => 'Sensor TPS Racing',
                'slug' => 'sensor-tps-racing',
                'description' => 'Sensor Throttle Position Sensor racing dengan respons lebih cepat dan akurat.',
                'specifications' => json_encode([
                    'Tipe' => 'Potentiometer',
                    'Resistance' => '1-5K Ohm',
                    'Konektor' => '3 Pin',
                    'Garansi' => '3 bulan'
                ]),
                'price' => 350000,
                'stock' => 20,
                'sku' => 'TPS-01-MJRT',
                'brand' => 'MJRT',
                'category' => 'Sensor',
                'images' => json_encode(['tps-1.jpg']),
                'is_featured' => false,
                'weight' => 0.05,
                'compatibility' => json_encode(['Semua motor FI'])
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
