<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminRoomController extends Controller
{
    /**
     * Display a listing of rooms.
     */
    public function index(): View
    {
        $rooms = Room::with('roomType')->orderBy('room_number')->get();

        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create(): View
    {
        $roomTypes = RoomType::orderBy('name')->get();

        return view('admin.rooms.create', compact('roomTypes'));
    }

    /**
     * Store a newly created room.
     */
    public function store(StoreRoomRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        Room::create($data);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room): View
    {
        $roomTypes = RoomType::orderBy('name')->get();

        return view('admin.rooms.edit', compact('room', 'roomTypes'));
    }

    /**
     * Update the specified room.
     */
    public function update(UpdateRoomRequest $request, Room $room): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        $room->update($data);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    /**
     * Remove the specified room.
     */
    public function destroy(Room $room): RedirectResponse
    {
        if ($room->bookings()->whereNotIn('status', ['cancelled', 'checked_out'])->exists()) {
            return back()->with('error', 'Kamar tidak dapat dihapus karena masih memiliki booking aktif.');
        }

        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
