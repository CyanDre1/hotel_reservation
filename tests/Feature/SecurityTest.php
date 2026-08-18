<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/dashboard')->assertOk();
    }

    public function test_admin_user_is_redirected_to_dashboard_after_login(): void
    {
        $admin = User::factory()->admin()->create();

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_guest_user_is_redirected_to_home_after_login(): void
    {
        $guest = User::factory()->create();

        $this->post('/login', [
            'email' => $guest->email,
            'password' => 'password',
        ])->assertRedirect(route('home', absolute: false));
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_password_is_hashed_with_bcrypt(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->assertNotSame('rahasia123', $user->password);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
        $this->assertStringStartsWith('$2y$', $user->password);
    }

    public function test_user_cannot_update_another_users_booking_status(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $outsider = User::factory()->create();

        $roomType = RoomType::create([
            'name' => 'Deluxe',
            'price_per_night' => 800000,
            'capacity' => 2,
        ]);
        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '201',
            'status' => 'available',
        ]);

        $booking = Booking::create([
            'user_id' => $owner->id,
            'room_id' => $room->id,
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'total_price' => 1600000,
            'status' => 'pending',
        ]);

        $this->actingAs($owner)->post("/bookings/{$booking->id}/cancel")->assertRedirect();
        $this->assertTrue($admin->can('update', $booking));
        $this->assertFalse($outsider->can('update', $booking));
        $this->assertFalse($outsider->can('view', $booking));

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'cancelled']);
    }
}
