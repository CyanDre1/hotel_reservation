<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function createAvailableRoom(): Room
    {
        $roomType = RoomType::create([
            'name' => 'Standard Room',
            'price_per_night' => 500000,
            'capacity' => 2,
        ]);

        return Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);
    }

    public function test_check_in_must_be_before_check_out(): void
    {
        $user = User::factory()->create();
        $room = $this->createAvailableRoom();

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'check_in' => now()->addDays(3)->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
        ]);

        $response->assertSessionHasErrors('check_out');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_prevents_double_booking_for_overlapping_dates(): void
    {
        $user = User::factory()->create();
        $room = $this->createAvailableRoom();

        Booking::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'total_price' => 1500000,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'check_in' => now()->addDays(4)->toDateString(),
            'check_out' => now()->addDays(6)->toDateString(),
        ]);

        $response->assertSessionHasErrors('check_in');
        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_room_is_available_for_non_overlapping_dates(): void
    {
        $user = User::factory()->create();
        $room = $this->createAvailableRoom();

        Booking::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'total_price' => 1500000,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'check_in' => now()->addDays(6)->toDateString(),
            'check_out' => now()->addDays(7)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('bookings', 2);
    }

    public function test_total_price_is_calculated_from_nights(): void
    {
        $user = User::factory()->create();
        $room = $this->createAvailableRoom();

        $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(4)->toDateString(),
        ]);

        $this->assertDatabaseHas('bookings', [
            'room_id' => $room->id,
            'status' => 'pending',
            'total_price' => '1500000.00',
        ]);
    }

    public function test_guest_cannot_access_booking_pages(): void
    {
        $this->get('/bookings')->assertRedirect('/login');
    }

    public function test_user_can_cancel_pending_booking(): void
    {
        $user = User::factory()->create();
        $room = $this->createAvailableRoom();

        $booking = Booking::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'total_price' => 1000000,
            'status' => 'pending',
        ]);

        $this->actingAs($user)->post("/bookings/{$booking->id}/cancel");

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_user_cannot_cancel_checked_in_booking(): void
    {
        $user = User::factory()->create();
        $room = $this->createAvailableRoom();

        $booking = Booking::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'check_in' => now()->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
            'total_price' => 1000000,
            'status' => 'checked_in',
        ]);

        $this->actingAs($user)->post("/bookings/{$booking->id}/cancel");

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'checked_in',
        ]);
    }

    public function test_user_cannot_access_another_users_booking(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $room = $this->createAvailableRoom();

        $booking = Booking::create([
            'user_id' => $owner->id,
            'room_id' => $room->id,
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'total_price' => 1000000,
            'status' => 'pending',
        ]);

        $this->actingAs($other)->get("/bookings/{$booking->id}")->assertForbidden();
        $this->actingAs($other)->post("/bookings/{$booking->id}/cancel")->assertForbidden();
    }
}
