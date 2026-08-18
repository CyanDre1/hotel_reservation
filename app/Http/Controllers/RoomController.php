<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms with filters.
     */
    public function index(Request $request): View
    {
        $checkIn = $request->query('check_in');
        $checkOut = $request->query('check_out');
        $roomTypeId = $request->query('room_type');
        $maxPrice = $request->query('max_price');

        $rooms = Room::with(['roomType', 'bookings'])
            ->when($checkIn && $checkOut, fn ($q) => $q->availableBetween($checkIn, $checkOut))
            ->when($roomTypeId, fn ($q) => $q->where('room_type_id', $roomTypeId))
            ->when($maxPrice, fn ($q) => $q->whereHas('roomType', fn ($rt) => $rt->where('price_per_night', '<=', $maxPrice)))
            ->get();

        $roomTypes = RoomType::orderBy('name')->get();

        $availability = $rooms->mapWithKeys(fn ($room) => [
            $room->id => $room->bookings
                ->whereNotIn('status', ['cancelled', 'checked_out'])
                ->map(fn ($b) => [$b->check_in->toDateString(), $b->check_out->toDateString()])
                ->values(),
        ]);

        return view('rooms.index', compact('rooms', 'roomTypes', 'checkIn', 'checkOut', 'roomTypeId', 'maxPrice', 'availability'));
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room): View
    {
        $room->load('roomType');

        return view('rooms.show', compact('room'));
    }
}
