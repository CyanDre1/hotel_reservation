<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    /**
     * Display the reports page.
     */
    public function index(): View
    {
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->startOfMonth()->subMonths($i));

        $monthLabels = $months->map(fn ($m) => $m->format('M Y'));

        $bookings = Booking::whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', $months->first())
            ->get();

        $bookingCounts = $months->map(fn ($m) => $bookings->filter(fn ($b) => Carbon::parse($b->created_at)->between(
            $m->copy()->startOfMonth(),
            $m->copy()->endOfMonth()
        ))->count());

        $revenues = $months->map(fn ($m) => (int) $bookings->filter(fn ($b) => Carbon::parse($b->created_at)->between(
            $m->copy()->startOfMonth(),
            $m->copy()->endOfMonth()
        ))->sum('total_price'));

        $totalRooms = max(Room::count(), 1);
        $allBookings = Booking::whereNotIn('status', ['cancelled'])->get();
        $bookedNights = $allBookings->sum(fn ($b) => max(Carbon::parse($b->check_in)->diffInDays(Carbon::parse($b->check_out)), 1));
        $occupancyRate = round(min($bookedNights / ($totalRooms * 30), 1) * 100, 1);

        return view('admin.reports.index', compact(
            'monthLabels',
            'bookingCounts',
            'revenues',
            'occupancyRate'
        ));
    }
}
