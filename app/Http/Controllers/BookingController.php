<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Show the booking form for a room.
     */
    public function create(Request $request, Room $room): View
    {
        $checkIn = $request->query('check_in');
        $checkOut = $request->query('check_out');
        $nights = null;
        $estimatedTotal = null;

        if ($checkIn && $checkOut) {
            $nights = max(Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut)), 1);
            $estimatedTotal = $room->roomType->price_per_night * $nights;
        }

        return view('booking.create', compact('room', 'checkIn', 'checkOut', 'nights', 'estimatedTotal'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $room = Room::with('roomType')->findOrFail($validated['room_id']);
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'room_id' => $room->id,
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
            'total_price' => $this->calculateTotalPrice($room, $checkIn, $checkOut),
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking berhasil dibuat. Menunggu konfirmasi admin.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Request $request, Booking $booking): View
    {
        $this->authorize('view', $booking);

        return view('booking.show', compact('booking'));
    }

    /**
     * Display a listing of the user's bookings.
     */
    public function index(Request $request): View
    {
        $bookings = $request->user()
            ->bookings()
            ->with('room.roomType')
            ->orderByDesc('created_at')
            ->get();

        return view('booking.index', compact('bookings'));
    }

    /**
     * Cancel the booking owned by the user.
     */
    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking dengan status ini tidak dapat dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Calculate the total price for a stay (price per night × number of nights).
     */
    private function calculateTotalPrice(Room $room, Carbon $checkIn, Carbon $checkOut): string
    {
        $nights = max($checkIn->diffInDays($checkOut), 1);

        return (string) round($room->roomType->price_per_night * $nights, 2);
    }
}
