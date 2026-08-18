<x-layouts.admin title="Tambah Kamar">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-ink">Tambah Kamar</h1>
        <a href="{{ route('admin.rooms.index') }}" class="text-sm text-muted hover:text-ink">&larr; Kembali</a>
    </div>

    <div class="max-w-xl bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="room_number" :value="__('Nomor Kamar')" />
                <x-text-input id="room_number" name="room_number" type="text" class="mt-1 block w-full"
                              value="{{ old('room_number') }}" required />
                <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="room_type_id" :value="__('Tipe Kamar')" />
                <select id="room_type_id" name="room_type_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                    <option value="">Pilih tipe</option>
                    @foreach ($roomTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('room_type_id') == $type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('room_type_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="status" :value="__('Status')" />
                <select id="status" name="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                    <option value="available" @selected(old('status') === 'available')>Tersedia</option>
                    <option value="maintenance" @selected(old('status') === 'maintenance')>Maintenance</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="image" :value="__('Foto Kamar')" />
                <input id="image" name="image" type="file" accept="image/*"
                       class="mt-1 block w-full text-sm" />
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>

            <div class="flex justify-end border-t border-gray-100 pt-4">
                <x-primary-button>{{ __('Simpan') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-layouts.admin>