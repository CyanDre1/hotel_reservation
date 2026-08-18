<x-layouts.admin title="Edit Tipe Kamar">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-ink">Edit Tipe Kamar</h1>
        <a href="{{ route('admin.room-types.index') }}" class="text-sm text-muted hover:text-ink">&larr; Kembali</a>
    </div>

    <div class="max-w-xl bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('admin.room-types.update', $roomType) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="name" :value="__('Nama')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                              value="{{ old('name', $roomType->name) }}" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" :value="__('Deskripsi')" />
                <textarea id="description" name="description" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 text-sm">{{ old('description', $roomType->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="price_per_night" :value="__('Harga / Malam (Rp)')" />
                    <x-text-input id="price_per_night" name="price_per_night" type="number" min="0" step="50000"
                                  class="mt-1 block w-full" value="{{ old('price_per_night', $roomType->price_per_night) }}" required />
                    <x-input-error :messages="$errors->get('price_per_night')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="capacity" :value="__('Kapasitas')" />
                    <x-text-input id="capacity" name="capacity" type="number" min="1"
                                  class="mt-1 block w-full" value="{{ old('capacity', $roomType->capacity) }}" required />
                    <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                </div>
            </div>

            <div class="flex justify-end border-t border-gray-100 pt-4">
                <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-layouts.admin>