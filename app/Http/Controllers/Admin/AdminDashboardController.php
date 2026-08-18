<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard overview.
     */
    public function index(): View
    {
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $availableRooms = Room::where('status', 'available')->count();
        $totalRooms = Room::count();

        $monthlyRevenue = Booking::query()
            ->whereNotIn('status', ['cancelled'])
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('total_price');

        $totalUsers = User::count();

        return view('admin.dashboard', compact(
            'totalBookings',
            'pendingBookings',
            'availableRooms',
            'totalRooms',
            'monthlyRevenue',
            'totalUsers'
        ));
    }
}
