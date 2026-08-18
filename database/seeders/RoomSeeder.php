<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = DB::table('room_types')->orderBy('id')->get();

        $rooms = [];
        $roomNumber = 101;

        foreach ($roomTypes as $roomType) {
            $count = match ($roomType->name) {
                'Standard Room' => 5,
                'Deluxe Room' => 4,
                'Family Room' => 3,
                'Suite' => 2,
                default => 2,
            };

            for ($i = 0; $i < $count; $i++) {
                $rooms[] = [
                    'room_type_id' => $roomType->id,
                    'room_number' => (string) $roomNumber,
                    'status' => 'available',
                    'image' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $roomNumber++;
            }
        }

        DB::table('rooms')->insert($rooms);
    }
}
