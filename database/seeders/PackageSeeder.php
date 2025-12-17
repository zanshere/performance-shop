<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Bore Up Harian',
                'slug' => 'paket-bore-up-harian',
                'description' => 'Paket bore up untuk kebutuhan harian dengan peningkatan performa yang smooth. Cocok untuk motor yang digunakan sehari-hari dengan konsumsi bahan bakar yang efisien.',
                'category' => 'harian',
                'price' => 3500000,
                'discount_price' => 3250000,
                'features' => json_encode([
                    'Peningkatan performa halus',
                    'Konsumsi bahan bakar efisien',
                    'Suara mesin tetap halus',
                    'Akselerasi lebih responsif',
                    'Daya tahan tinggi untuk penggunaan harian'
                ]),
                'included_items' => json_encode([
                    'Piston + Ring Standard',
                    'Blok Piston OEM',
                    'Service kepala silinder',
                    'Gasket set',
                    'Pelumas racing',
                    'Tune up mesin',
                    'Test ride'
                ]),
                'compatible_bikes' => json_encode([
                    'Honda Beat',
                    'Honda Vario',
                    'Yamaha Mio',
                    'Suzuki Nex',
                    'Honda Scoopy'
                ]),
                'duration_days' => 2,
                'difficulty_level' => 'beginner',
                'power_gain_percentage' => 15,
                'is_featured' => true,
                'order_count' => 45,
                'view_count' => 320,
            ],
            [
                'name' => 'Paket Bore Up Sport',
                'slug' => 'paket-bore-up-sport',
                'description' => 'Paket bore up untuk performa lebih agresif, cocok untuk touring dan city riding. Peningkatan signifikan pada akselerasi dan top speed.',
                'category' => 'sport',
                'price' => 6500000,
                'discount_price' => null,
                'features' => json_encode([
                    'Akselerasi sangat responsif',
                    'Top speed meningkat signifikan',
                    'Performa stabil untuk touring',
                    'Suara mesin sporty',
                    'Respons throttle lebih baik'
                ]),
                'included_items' => json_encode([
                    'Piston Racing 62mm',
                    'Blok Piston Racing',
                    'Klep Racing',
                    'CDI Racing',
                    'Karburator Racing',
                    'Knalpot Racing',
                    'Service lengkap mesin',
                    'Dyno test',
                    'Tune up khusus'
                ]),
                'compatible_bikes' => json_encode([
                    'Honda Beat FI',
                    'Honda Vario 150',
                    'Yamaha NMAX',
                    'Honda PCX',
                    'Yamaha Aerox'
                ]),
                'duration_days' => 3,
                'difficulty_level' => 'intermediate',
                'power_gain_percentage' => 35,
                'is_featured' => true,
                'order_count' => 28,
                'view_count' => 245,
            ],
            [
                'name' => 'Paket Bore Up Racing',
                'slug' => 'paket-bore-up-racing',
                'description' => 'Paket bore up full racing untuk balap dan performa maksimal. Menggunakan komponen racing terbaik dengan pengerjaan oleh mekanik berpengalaman.',
                'category' => 'racing',
                'price' => 12000000,
                'discount_price' => 11500000,
                'features' => json_encode([
                    'Performa maksimal untuk racing',
                    'Akselerasi sangat cepat',
                    'Top speed sangat tinggi',
                    'Daya tahan extreme',
                    'Custom tune untuk track',
                    'Support teknis khusus'
                ]),
                'included_items' => json_encode([
                    'Piston Full Racing 63mm',
                    'Blok Piston Big Bore',
                    'Klep Full Race',
                    'Noken As Racing',
                    'TB Downdraft 28mm',
                    'ECU Racing Programmable',
                    'Knalpot Full System',
                    'Dyno Test & Tuning',
                    'Service Extreme',
                    'Test Ride & Setup'
                ]),
                'compatible_bikes' => json_encode([
                    'Yamaha R15',
                    'Honda CBR150R',
                    'Kawasaki Ninja 150',
                    'Suzuki GSX-R150',
                    'Yamaha MT-15'
                ]),
                'duration_days' => 5,
                'difficulty_level' => 'expert',
                'power_gain_percentage' => 60,
                'is_featured' => true,
                'order_count' => 12,
                'view_count' => 180,
            ],
            [
                'name' => 'Paket Bore Up Custom',
                'slug' => 'paket-bore-up-custom',
                'description' => 'Paket bore up custom sesuai kebutuhan spesifik Anda. Konsultasi langsung dengan mekanik berpengalaman untuk mendapatkan setup terbaik.',
                'category' => 'custom',
                'price' => 8000000,
                'discount_price' => null,
                'features' => json_encode([
                    'Custom sesuai kebutuhan',
                    'Konsultasi langsung dengan ahli',
                    'Komponen pilihan Anda',
                    'Flexible budget',
                    'Setup khusus untuk tujuan tertentu'
                ]),
                'included_items' => json_encode([
                    'Konsultasi ahli',
                    'Custom komponen selection',
                    'Pengerjaan oleh mekanik expert',
                    'Dyno Test & Custom Tuning',
                    'Test Ride & Fine Tuning',
                    'Garansi khusus'
                ]),
                'compatible_bikes' => json_encode([
                    'Semua tipe motor',
                    'Custom request'
                ]),
                'duration_days' => 7,
                'difficulty_level' => 'expert',
                'power_gain_percentage' => null,
                'is_featured' => false,
                'order_count' => 8,
                'view_count' => 95,
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
