<x-layouts.admin title="Manajemen Booking">
    <div class="flex items-end justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-ink">Manajemen Booking</h1>
            <p class="text-sm text-muted mt-1">{{ $bookings->count() }} booking.</p>
        </div>

        <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex items-center gap-2">
            <select name="status" onchange="this.form.submit()"
                    class="rounded-md border-gray-300 text-sm">
                <option value="">Semua Status</option>
                @foreach (['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'] as $s)
                    <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
            @if ($status)
                <a href="{{ route('admin.bookings.index') }}" class="text-sm text-muted hover:text-ink">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-surface">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-muted">ID</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Tamu</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Kamar</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Check-in</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Check-out</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Total</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($bookings as $booking)
                    <tr class="hover:bg-surface/50 transition">
                        <td class="px-4 py-3">#{{ $booking->id }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-ink">{{ $booking->user->name }}</p>
                            <p class="text-xs text-muted">{{ $booking->user->email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</td>
                        <td class="px-4 py-3">{{ $booking->check_in->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $booking->check_out->format('d M Y') }}</td>
                        <td class="px-4 py-3 font-medium">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <x-status-badge :status="$booking->status" />
                        </td>
                        <td class="px-4 py-3">
                            @if (in_array($booking->status, ['pending', 'confirmed', 'checked_in']))
                                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}" class="inline-flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()"
                                            class="rounded-md border-gray-300 text-xs py-1">
                                        @foreach ($nextStatuses[$booking->status] ?? [] as $s)
                                            <option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                <span class="text-xs text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-muted">Tidak ada booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>