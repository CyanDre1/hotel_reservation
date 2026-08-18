<x-layouts.public :title="$room->roomType->name.' — Kamar '.$room->room_number">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('rooms.index') }}" class="text-sm text-muted hover:text-ink">&larr; Kembali ke daftar</a>
    </div>

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="bg-primary-light/20 rounded-lg h-72 sm:h-96 flex items-center justify-center overflow-hidden">
                @if ($room->image)
                    <img src="{{ asset('storage/'.$room->image) }}" alt="{{ $room->roomType->name }}"
                         class="h-full w-full object-cover" />
                @else
                    <span class="text-6xl font-bold text-primary/30">{{ strtoupper($room->roomType->name[0]) }}</span>
                @endif
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-ink">{{ $room->roomType->name }}</h1>
                    <span class="text-xs px-3 py-1 rounded-full bg-surface text-muted border border-gray-200">Kamar {{ $room->room_number }}</span>
                </div>
                <p class="mt-2 text-lg font-semibold text-primary">
                    Rp {{ number_format($room->roomType->price_per_night, 0, ',', '.') }}
                    <span class="text-sm text-muted font-normal">/ malam</span>
                </p>

                <h2 class="mt-8 text-xl font-semibold text-ink">Deskripsi</h2>
                <p class="mt-2 text-muted leading-relaxed">{{ $room->roomType->description }}</p>

                <h2 class="mt-8 text-xl font-semibold text-ink">Fasilitas</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach (['WiFi gratis', 'AC', 'TV LED', 'Kamar mandi dalam', 'Kasur premium', 'Air minum'] as $facility)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface border border-gray-200 text-sm text-ink">
                            <svg class="h-4 w-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            {{ $facility }}
                        </span>
                    @endforeach
                </div>
                <p class="mt-2 text-xs text-muted">Kapasitas: {{ $room->roomType->capacity }} tamu</p>
            </div>
        </div>

        <aside>
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 sticky top-20">
                <h2 class="font-semibold text-ink mb-4">Cek Ketersediaan & Booking</h2>
                <form method="GET" action="{{ route('bookings.create', $room) }}" class="space-y-4">
                    <div>
                        <label for="check_in" class="block text-sm font-medium text-ink">Check-in</label>
                        <input type="date" id="check_in" name="check_in" value="{{ old('check_in', request('check_in')) }}"
                               min="{{ now()->toDateString() }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm" />
                    </div>
                    <div>
                        <label for="check_out" class="block text-sm font-medium text-ink">Check-out</label>
                        <input type="date" id="check_out" name="check_out" value="{{ old('check_out', request('check_out')) }}"
                               min="{{ now()->toDateString() }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm" />
                    </div>

                    <button type="submit"
                            class="w-full px-4 py-3 bg-primary text-white font-semibold rounded-md hover:bg-primary-light transition">
                        Pesan Sekarang
                    </button>
                    <p class="text-xs text-muted text-center">Anda akan diminta login untuk melanjutkan.</p>
                </form>
            </div>
        </aside>
    </div>
</x-layouts.public>
