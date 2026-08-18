<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private function createRoomType(array $overrides = []): RoomType
    {
        return RoomType::create(array_merge([
            'name' => 'Standard Room',
            'price_per_night' => 500000,
            'capacity' => 2,
        ], $overrides));
    }

    public function test_home_page_renders_with_room_types(): void
    {
        $roomType = $this->createRoomType();

        $this->get('/')
            ->assertOk()
            ->assertSee($roomType->name);
    }

    public function test_rooms_index_lists_rooms(): void
    {
        $roomType = $this->createRoomType();
        Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $this->get('/rooms')
            ->assertOk()
            ->assertSee('101');
    }

    public function test_rooms_index_filters_by_availability(): void
    {
        $user = User::factory()->create();
        $roomType = $this->createRoomType();

        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $freeRoom = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '102',
            'status' => 'available',
        ]);

        Booking::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(4)->toDateString(),
            'total_price' => 1000000,
            'status' => 'confirmed',
        ]);

        $response = $this->get('/rooms?check_in='.now()->addDays(3)->toDateString().'&check_out='.now()->addDays(5)->toDateString());

        $response->assertOk()
            ->assertSee('102')
            ->assertDontSee('>101</a>');
    }

    public function test_room_detail_page_renders(): void
    {
        $roomType = $this->createRoomType();
        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $this->get("/rooms/{$room->id}")
            ->assertOk()
            ->assertSee($roomType->name)
            ->assertSee('Pesan Sekarang');
    }

    public function test_booking_form_requires_login(): void
    {
        $roomType = $this->createRoomType();
        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $this->get("/rooms/{$room->id}/book")->assertRedirect('/login');
    }

    public function test_booking_form_shows_summary_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $roomType = $this->createRoomType();
        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $this->actingAs($user)
            ->get("/rooms/{$room->id}/book?check_in=".now()->addDays(1)->toDateString().'&check_out='.now()->addDays(3)->toDateString())
            ->assertOk()
            ->assertSee('Ringkasan Pesanan')
            ->assertSee('1.000.000');
    }

    public function test_login_and_register_pages_render(): void
    {
        $this->get('/login')->assertOk()->assertSee('Log in');
        $this->get('/register')->assertOk()->assertSee('Register');
    }
}
