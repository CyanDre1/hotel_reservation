<x-layouts.public title="Checkout Booking">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-ink mb-1">Konfirmasi Booking</h1>
        <p class="text-sm text-muted mb-6">Periksa kembali detail pesanan Anda sebelum konfirmasi.</p>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-ink">Ringkasan Pesanan</h2>
                    <span class="text-xs px-3 py-1 rounded-full bg-surface border border-gray-200 text-muted">Kamar {{ $room->room_number }}</span>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-ink">{{ $room->roomType->name }}</h3>
                    <p class="mt-1 text-sm text-muted">{{ $room->roomType->description }}</p>

                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-muted">Harga per malam</dt>
                            <dd class="font-medium text-ink">Rp {{ number_format($room->roomType->price_per_night, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted">Check-in</dt>
                            <dd class="font-medium text-ink">{{ $checkIn ? \Carbon\Carbon::parse($checkIn)->format('d M Y') : '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted">Check-out</dt>
                            <dd class="font-medium text-ink">{{ $checkOut ? \Carbon\Carbon::parse($checkOut)->format('d M Y') : '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted">Jumlah malam</dt>
                            <dd class="font-medium text-ink">{{ $nights ?: '—' }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-gray-100 pt-3 text-base">
                            <dt class="font-medium text-ink">Total</dt>
                            <dd class="font-bold text-primary">Rp {{ $estimatedTotal ? number_format($estimatedTotal, 0, ',', '.') : '—' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="sm:col-span-2 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-ink">Data Tamu</h2>
                </div>
                <form method="POST" action="{{ route('bookings.store') }}" class="p-6 space-y-5">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    <input type="hidden" name="check_in" value="{{ $checkIn }}">
                    <input type="hidden" name="check_out" value="{{ $checkOut }}">

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                          value="{{ old('name', auth()->user()->name) }}" readonly />
                        </div>
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                          value="{{ old('email', auth()->user()->email) }}" readonly />
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-md bg-red-50 border border-red-200 p-4 text-sm text-danger">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                        <p class="text-sm text-muted">Status: <span class="font-medium text-warning">pending</span></p>
                        <x-primary-button>
                            {{ __('Konfirmasi Booking') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.public>
