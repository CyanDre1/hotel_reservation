<x-layouts.public title="Beranda">
    <div x-data="{
        images: ['{{ asset('images/hero-hotel1.png') }}', '{{ asset('images/hero-hotel2.png') }}', '{{ asset('images/hero-hotel3.png') }}'],
        current: 0,
        init() {
            setInterval(() => {
                this.current = (this.current + 1) % this.images.length;
            }, 4000);
        }
    }">
    <section class="-mx-4 sm:-mx-6 -mt-8 relative text-white overflow-hidden">
        <template x-for="(img, index) in images" :key="index">
            <img :src="img"
                 alt="Hotel"
                 class="absolute inset-0 w-full h-full object-cover object-[center_35%] transition-opacity duration-1000"
                 x-bind:class="current === index ? 'opacity-100' : 'opacity-0'">
        </template>

        <div class="absolute inset-0 bg-primary/75"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-20 sm:py-28">
            <div class="max-w-2xl">
                <h1 class="text-4xl sm:text-5xl font-bold tracking-tight leading-tight">
                    Nikmati Menginap Nyaman di {{ config('app.name') }}
                </h1>
                <p class="mt-4 text-lg text-white/80">
                    Pesan kamar favorit Anda secara online dengan harga terbaik. Cek ketersediaan kamar secara real-time dan nikmati pelayanan terbaik kami.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('rooms.index') }}"
                       class="inline-flex items-center justify-center px-6 py-3 bg-white text-primary font-semibold rounded-md hover:bg-white/90 transition">
                        Pesan Sekarang
                    </a>
                    <a href="{{ route('rooms.index') }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-white/40 text-white font-semibold rounded-md hover:bg-white/10 transition">
                        Lihat Semua Kamar
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="flex justify-center gap-2 mt-6">
        <template x-for="(img, index) in images" :key="index">
            <button @click="current = index"
                    class="h-2 rounded-full transition-all duration-300"
                    x-bind:class="current === index ? 'bg-gray-600 w-6' : 'bg-gray-300 w-2 hover:bg-gray-400'"
                    :aria-label="'Gambar ' + (index + 1)"></button>
        </template>
    </div>
</div>

    <section class="mt-14">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-ink">Tipe Kamar Kami</h2>
                <p class="text-sm text-muted mt-1">Pilih tipe kamar yang paling sesuai dengan kebutuhan Anda.</p>
            </div>
            <a href="{{ route('rooms.index') }}" class="text-sm font-medium text-primary hover:underline">Lihat semua →</a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($roomTypes as $roomType)
                <a href="{{ route('rooms.index', ['room_type' => $roomType->id]) }}"
                   class="group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="h-36 bg-primary-light/20 flex items-center justify-center overflow-hidden">
                        @php $roomImage = $roomType->rooms->first()?->image; @endphp
                        @if ($roomImage)
                            <img src="{{ asset('storage/' . $roomImage) }}"
                                 alt="{{ $roomType->name }}"
                                 class="h-full w-full object-cover">
                        @else
                            <span class="text-4xl font-bold text-primary/30 group-hover:text-primary/50 transition">
                                {{ strtoupper($roomType->name[0]) }}
                            </span>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-ink group-hover:text-primary transition">{{ $roomType->name }}</h3>
                        <p class="mt-1 text-sm text-muted line-clamp-2">{{ $roomType->description }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="font-semibold text-primary">Rp {{ number_format($roomType->price_per_night, 0, ',', '.') }}<span class="text-xs text-muted font-normal"> / malam</span></span>
                            <span class="text-xs text-muted">{{ $roomType->capacity }} tamu</span>
                        </div>
                        <div class="mt-3 text-xs text-muted">{{ $roomType->rooms_count }} kamar tersedia</div>
                    </div>
                </a>
            @empty
                <p class="text-muted col-span-full">Belum ada tipe kamar.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-16">
        <h2 class="text-2xl font-bold text-ink mb-6">Fasilitas Hotel</h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="h-10 w-10 rounded-md bg-primary/10 flex items-center justify-center text-primary mb-3">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 12l2 2 4-4" /></svg>
                </div>
                <h3 class="font-semibold text-ink">Koneksi WiFi Cepat</h3>
                <p class="mt-1 text-sm text-muted">Internet gratis berkecepatan tinggi di seluruh area hotel.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="h-10 w-10 rounded-md bg-primary/10 flex items-center justify-center text-primary mb-3">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v12H4zM4 13h16M8 21h8" /></svg>
                </div>
                <h3 class="font-semibold text-ink">Kolam Renang</h3>
                <p class="mt-1 text-sm text-muted">Kolam renang outdoor dengan area bersantai.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="h-10 w-10 rounded-md bg-primary/10 flex items-center justify-center text-primary mb-3">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-4.5-7-10a7 7 0 1114 0c0 5.5-7 10-7 10zM9 9l2 2 4-4" /></svg>
                </div>
                <h3 class="font-semibold text-ink">Restoran & Kafe</h3>
                <p class="mt-1 text-sm text-muted">Sajian lokal dan internasional sepanjang hari.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="h-10 w-10 rounded-md bg-primary/10 flex items-center justify-center text-primary mb-3">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h3 class="font-semibold text-ink">Parkir Gratis</h3>
                <p class="mt-1 text-sm text-muted">Area parkir luas dan aman untuk tamu hotel.</p>
            </div>
        </div>
    </section>
</x-layouts.public>