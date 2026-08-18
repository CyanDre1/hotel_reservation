<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        return $admin;
    }

    private function createRoomType(array $overrides = []): RoomType
    {
        return RoomType::create(array_merge([
            'name' => 'Standard Room',
            'price_per_night' => 500000,
            'capacity' => 2,
        ], $overrides));
    }

    public function test_admin_can_view_dashboard_with_stats(): void
    {
        $this->actingAsAdmin();

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Total Booking');
    }

    public function test_guest_cannot_access_admin_pages(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/rooms')->assertForbidden();
        $this->get('/admin/bookings')->assertForbidden();
        $this->get('/admin/reports')->assertForbidden();
    }

    public function test_admin_can_crud_room_type(): void
    {
        $this->actingAsAdmin();

        $this->post('/admin/room-types', [
            'name' => 'Junior Suite',
            'description' => 'Suite kecil',
            'price_per_night' => 1500000,
            'capacity' => 2,
        ])->assertRedirect();

        $roomType = RoomType::where('name', 'Junior Suite')->firstOrFail();
        $this->assertDatabaseHas('room_types', ['name' => 'Junior Suite']);

        $this->put("/admin/room-types/{$roomType->id}", [
            'name' => 'Junior Suite Deluxe',
            'description' => 'Suite kecil premium',
            'price_per_night' => 1600000,
            'capacity' => 3,
        ])->assertRedirect();

        $this->assertDatabaseHas('room_types', ['name' => 'Junior Suite Deluxe', 'capacity' => 3]);

        $this->delete("/admin/room-types/{$roomType->id}")->assertRedirect();
        $this->assertDatabaseMissing('room_types', ['id' => $roomType->id]);
    }

    public function test_admin_can_create_room_with_image(): void
    {
        Storage::fake('public');
        $this->actingAsAdmin();
        $roomType = $this->createRoomType();

        $this->post('/admin/rooms', [
            'room_type_id' => $roomType->id,
            'room_number' => '301',
            'status' => 'available',
            'image' => UploadedFile::fake()->image('room.jpg'),
        ])->assertRedirect();

        $room = Room::where('room_number', '301')->firstOrFail();
        $this->assertNotNull($room->image);
        $this->assertStringStartsWith('rooms/', $room->image);
        Storage::disk('public')->assertExists($room->image);
    }

    public function test_admin_can_update_booking_status(): void
    {
        $admin = $this->actingAsAdmin();
        $guest = User::factory()->create();
        $roomType = $this->createRoomType();
        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $booking = Booking::create([
            'user_id' => $guest->id,
            'room_id' => $room->id,
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'total_price' => 1000000,
            'status' => 'pending',
        ]);

        $this->put("/admin/bookings/{$booking->id}/status", ['status' => 'confirmed'])->assertRedirect();
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'confirmed']);
    }

    public function test_admin_cannot_update_booking_status_to_invalid(): void
    {
        $this->actingAsAdmin();
        $guest = User::factory()->create();
        $roomType = $this->createRoomType();
        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $booking = Booking::create([
            'user_id' => $guest->id,
            'room_id' => $room->id,
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'total_price' => 1000000,
            'status' => 'pending',
        ]);

        $this->put("/admin/bookings/{$booking->id}/status", ['status' => 'hacked'])
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'pending']);
    }

    public function test_admin_can_update_user_role(): void
    {
        $this->actingAsAdmin();
        $guest = User::factory()->create();

        $this->put("/admin/users/{$guest->id}", [
            'name' => $guest->name,
            'email' => $guest->email,
            'role' => 'admin',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $guest->id, 'role' => 'admin']);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $this->delete("/admin/users/{$admin->id}")->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_cannot_delete_room_with_active_bookings(): void
    {
        $this->actingAsAdmin();
        $guest = User::factory()->create();
        $roomType = $this->createRoomType();
        $room = Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        Booking::create([
            'user_id' => $guest->id,
            'room_id' => $room->id,
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'total_price' => 1000000,
            'status' => 'confirmed',
        ]);

        $this->delete("/admin/rooms/{$room->id}")->assertRedirect();
        $this->assertDatabaseHas('rooms', ['id' => $room->id]);
    }

    public function test_admin_can_view_reports(): void
    {
        $this->actingAsAdmin();

        $this->get('/admin/reports')
            ->assertOk()
            ->assertSee('Laporan')
            ->assertSee('bookingsChart');
    }

    public function test_admin_room_type_delete_blocked_when_has_rooms(): void
    {
        $this->actingAsAdmin();
        $roomType = $this->createRoomType();
        Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $this->delete("/admin/room-types/{$roomType->id}")->assertRedirect();
        $this->assertDatabaseHas('room_types', ['id' => $roomType->id]);
    }

    public function test_admin_can_create_room_type_via_json_for_modal(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/admin/room-types', [
            'name' => 'Penthouse',
            'description' => 'Kamar premium',
            'price_per_night' => 5000000,
            'capacity' => 2,
        ]);

        $response->assertCreated()
            ->assertJsonPath('name', 'Penthouse');

        $this->assertDatabaseHas('room_types', ['name' => 'Penthouse']);
    }

    public function test_admin_room_type_json_delete_blocked_when_has_rooms(): void
    {
        $this->actingAsAdmin();
        $roomType = $this->createRoomType();
        Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $this->deleteJson("/admin/room-types/{$roomType->id}")
            ->assertStatus(422);

        $this->assertDatabaseHas('room_types', ['id' => $roomType->id]);
    }

    public function test_rooms_index_renders_cards_and_client_side_filter_data(): void
    {
        $roomType = $this->createRoomType();
        Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $this->get('/rooms')
            ->assertOk()
            ->assertSee('Lihat Detail')
            ->assertSee('Kamar 101')
            ->assertSee('window.roomFilterData')
            ->assertSee('matches');
    }

    public function test_admin_rooms_index_lists_rooms(): void
    {
        $this->actingAsAdmin();
        $roomType = $this->createRoomType();
        Room::create([
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'status' => 'available',
        ]);

        $this->get('/admin/rooms')
            ->assertOk()
            ->assertSee('101');
    }
}
