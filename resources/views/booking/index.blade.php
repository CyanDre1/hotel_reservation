<x-layouts.public title="Riwayat Booking">
    <div class="flex items-end justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-ink">Riwayat Booking Saya</h1>
            <p class="text-sm text-muted mt-1">Semua pemesanan kamar Anda.</p>
        </div>
        <a href="{{ route('rooms.index') }}" class="text-sm font-medium text-primary hover:underline">Booking baru →</a>
    </div>

    @if ($bookings->isEmpty())
        <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
            <p class="text-muted">Belum ada booking.</p>
            <a href="{{ route('rooms.index') }}" class="inline-block mt-4 text-sm font-medium text-primary hover:underline">
                Lihat kamar & mulai booking
            </a>
        </div>
    @else
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-surface">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-muted">Kamar</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Check-in</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Check-out</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Total</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-surface/50 transition">
                            <td class="px-4 py-3">
                                <a href="{{ route('rooms.show', $booking->room) }}" class="font-medium text-ink hover:text-primary">
                                    Kamar {{ $booking->room->room_number }}
                                </a>
                                <p class="text-xs text-muted">{{ $booking->room->roomType->name }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $booking->check_in->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ $booking->check_out->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-medium">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <x-status-badge :status="$booking->status" />
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap space-x-3">
                                <a href="{{ route('bookings.show', $booking) }}" class="text-primary hover:underline">Detail</a>
                                @if (in_array($booking->status, ['pending', 'confirmed']))
                                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="inline"
                                          onsubmit="return confirm('Batalkan booking ini?')">
                                        @csrf
                                        <button type="submit" class="text-danger hover:underline">Batalkan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.public>
