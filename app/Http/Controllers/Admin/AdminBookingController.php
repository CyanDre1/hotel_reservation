<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBookingStatusRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $bookings = Booking::with(['user', 'room.roomType'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->get();

        $nextStatuses = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['checked_in', 'cancelled'],
            'checked_in' => ['checked_out'],
        ];

        return view('admin.bookings.index', compact('bookings', 'status', 'nextStatuses'));
    }

    /**
     * Update the booking status.
     */
    public function updateStatus(UpdateBookingStatusRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update(['status' => $request->validated('status')]);

        return back()->with('success', 'Status booking #'.$booking->id.' diubah menjadi '.$request->validated('status').'.');
    }
}
