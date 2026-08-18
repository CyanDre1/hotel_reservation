<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminRoomTypeController extends Controller
{
    /**
     * Display a listing of room types.
     */
    public function index(): View
    {
        $roomTypes = RoomType::withCount('rooms')->orderBy('name')->get();

        return view('admin.room-types.index', compact('roomTypes'));
    }

    /**
     * Show the form for creating a new room type.
     */
    public function create(): View
    {
        return view('admin.room-types.create');
    }

    /**
     * Store a newly created room type.
     */
    public function store(StoreRoomTypeRequest $request): RedirectResponse|JsonResponse
    {
        $roomType = RoomType::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($roomType, 201);
        }

        return redirect()->route('admin.room-types.index')->with('success', 'Tipe kamar berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified room type.
     */
    public function edit(RoomType $roomType): View
    {
        return view('admin.room-types.edit', compact('roomType'));
    }

    /**
     * Update the specified room type.
     */
    public function update(UpdateRoomTypeRequest $request, RoomType $roomType): RedirectResponse|JsonResponse
    {
        $roomType->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json($roomType);
        }

        return redirect()->route('admin.room-types.index')->with('success', 'Tipe kamar berhasil diperbarui.');
    }

    /**
     * Remove the specified room type.
     */
    public function destroy(Request $request, RoomType $roomType): RedirectResponse|JsonResponse
    {
        if ($roomType->rooms()->exists()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Tipe kamar tidak dapat dihapus karena masih memiliki kamar.'], 422);
            }

            return back()->with('error', 'Tipe kamar tidak dapat dihapus karena masih memiliki kamar.');
        }

        $roomType->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Tipe kamar berhasil dihapus.']);
        }

        return redirect()->route('admin.room-types.index')->with('success', 'Tipe kamar berhasil dihapus.');
    }
}
