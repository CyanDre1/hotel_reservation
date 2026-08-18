<x-layouts.public title="Detail Booking">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-ink">Detail Booking #{{ $booking->id }}</h1>
            <a href="{{ route('bookings.index') }}" class="text-sm text-muted hover:text-ink">&larr; Kembali</a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm divide-y divide-gray-100">
            <div class="p-6 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-muted">Kamar</p>
                    <p class="font-medium text-ink">{{ $booking->room->room_number }} — {{ $booking->room->roomType->name }}</p>
                </div>
                <div>
                    <p class="text-muted">Status</p>
                    <x-status-badge :status="$booking->status" />
                </div>
                <div>
                    <p class="text-muted">Check-in</p>
                    <p class="font-medium text-ink">{{ $booking->check_in->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-muted">Check-out</p>
                    <p class="font-medium text-ink">{{ $booking->check_out->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-muted">Total Malam</p>
                    <p class="font-medium text-ink">{{ $booking->check_in->diffInDays($booking->check_out) }} malam</p>
                </div>
                <div>
                    <p class="text-muted">Total Harga</p>
                    <p class="font-semibold text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="p-6">
                @if (in_array($booking->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                          onsubmit="return confirm('Batalkan booking ini?')">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-danger hover:underline">
                            Batalkan Booking
                        </button>
                    </form>
                @else
                    <p class="text-sm text-muted">Booking ini tidak dapat dibatalkan lagi.</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.public>
