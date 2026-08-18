<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the landing page.
     */
    public function home(): View
    {
        $roomTypes = RoomType::withCount('rooms')
            ->with(['rooms' => fn ($query) => $query->whereNotNull('image')])
            ->orderBy('price_per_night')
            ->get();

        return view('home', compact('roomTypes'));
    }
}
