<x-layouts.public title="Daftar Kamar">
    <div
        x-data="{
            rooms: window.roomFilterData.rooms,
            checkIn: @js($checkIn),
            checkOut: @js($checkOut),
            type: @js($roomTypeId ? (string) $roomTypeId : ''),
            maxPrice: @js($maxPrice),
            availability: @js($availability->toArray()),
            matches(room) {
                if (this.type && String(room.room_type_id) !== String(this.type)) return false;
                if (this.maxPrice && room.price > Number(this.maxPrice)) return false;
                if (this.checkIn && this.checkOut) {
                    const ranges = this.availability[room.id] || [];
                    if (ranges.some(([from, to]) => this.checkIn < to && from < this.checkOut)) return false;
                }
                return true;
            },
            get visibleCount() {
                return this.rooms.filter(r => this.matches(r)).length;
            },
            reset() {
                this.checkIn = '';
                this.checkOut = '';
                this.type = '';
                this.maxPrice = '';
            }
        }"
    >
        <div class="flex items-end justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-ink">Daftar Kamar</h1>
                <p class="text-sm text-muted mt-1">Temukan kamar yang cocok untuk Anda.</p>
            </div>
            <p class="text-sm text-muted"><span x-text="visibleCount"></span> kamar</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-4">
            <aside class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-lg p-5 space-y-5 sticky top-20">
                    <h2 class="font-semibold text-ink">Filter</h2>

                    <div>
                        <label for="check_in" class="block text-sm font-medium text-ink">Check-in</label>
                        <input type="date" id="check_in" x-model="checkIn" min="{{ now()->toDateString() }}"
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm" />
                    </div>

                    <div>
                        <label for="check_out" class="block text-sm font-medium text-ink">Check-out</label>
                        <input type="date" id="check_out" x-model="checkOut" min="{{ now()->toDateString() }}"
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm" />
                    </div>

                    <div>
                        <label for="room_type" class="block text-sm font-medium text-ink">Tipe Kamar</label>
                        <select id="room_type" x-model="type"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                            <option value="">Semua Tipe</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="max_price" class="block text-sm font-medium text-ink">Harga Maksimal / Malam (Rp)</label>
                        <input type="number" id="max_price" x-model="maxPrice" min="0" step="50000"
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm" />
                    </div>

                    <div class="flex gap-2">
                        <button type="button" @click="reset()"
                                class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium text-muted rounded-md hover:text-ink transition">
                            Reset
                        </button>
                    </div>
                </div>
            </aside>

            <div class="lg:col-span-3">
                <div x-show="visibleCount === 0" x-cloak
                     class="bg-white border border-gray-200 rounded-lg p-12 text-center">
                    <p class="text-muted">Tidak ada kamar yang cocok dengan filter.</p>
                    <button type="button" @click="reset()"
                            class="inline-block mt-4 text-sm font-medium text-primary hover:underline">
                        Reset filter
                    </button>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse ($rooms as $room)
                        @php
                            $roomJs = [
                                'id' => $room->id,
                                'room_type_id' => $room->room_type_id,
                                'price' => (int) $room->roomType->price_per_night,
                            ];
                        @endphp
                        <div x-show="matches(@js($roomJs))"
                             class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                            <div class="h-40 bg-primary-light/20 flex items-center justify-center">
                                @if ($room->image)
                                    <img src="{{ asset('storage/'.$room->image) }}" alt="{{ $room->roomType->name }}"
                                         class="h-full w-full object-cover" />
                                @else
                                    <span class="text-3xl font-bold text-primary/30">{{ strtoupper($room->roomType->name[0]) }}</span>
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-ink">{{ $room->roomType->name }}</h3>
                                    <span class="text-xs text-muted">Kamar {{ $room->room_number }}</span>
                                </div>
                                <p class="mt-1 text-sm text-muted line-clamp-2 flex-1">{{ $room->roomType->description }}</p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="font-semibold text-primary">Rp {{ number_format($room->roomType->price_per_night, 0, ',', '.') }}<span class="text-xs text-muted font-normal"> / malam</span></span>
                                    <a href="{{ route('rooms.show', $room) }}"
                                       class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-primary-light transition">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white border border-gray-200 rounded-lg p-12 text-center">
                            <p class="text-muted">Tidak ada kamar yang tersedia.</p>
                            <a href="{{ route('rooms.index') }}" class="inline-block mt-4 text-sm font-medium text-primary hover:underline">
                                Reset filter
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        window.roomFilterData = @js([
            'rooms' => $rooms->map(fn ($r) => [
                'id' => $r->id,
                'room_type_id' => $r->room_type_id,
                'price' => (int) $r->roomType->price_per_night,
            ])->values()->toArray(),
        ]);
    </script>
</x-layouts.public>