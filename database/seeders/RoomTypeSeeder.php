<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Standard Room',
                'description' => 'Kamar nyaman dengan kasur queen, cocok untuk 2 orang. Dilengkapi TV, AC, dan kamar mandi dalam.',
                'price_per_night' => 500000,
                'capacity' => 2,
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'Kamar lebih luas dengan pemandangan kolam renang, kasur king, dan fasilitas premium.',
                'price_per_night' => 850000,
                'capacity' => 2,
            ],
            [
                'name' => 'Family Room',
                'description' => 'Kamar luas untuk keluarga hingga 4 orang, dilengkapi 2 kasur double dan sofa bed.',
                'price_per_night' => 1200000,
                'capacity' => 4,
            ],
            [
                'name' => 'Suite',
                'description' => 'Kamar paling mewah dengan ruang tamu terpisah, bathtub, dan layanan butler.',
                'price_per_night' => 2000000,
                'capacity' => 4,
            ],
        ];

        foreach ($roomTypes as $roomType) {
            $roomType['created_at'] = now();
            $roomType['updated_at'] = now();
            DB::table('room_types')->insert($roomType);
        }
    }
}
