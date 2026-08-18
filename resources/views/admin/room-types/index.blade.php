<x-layouts.admin title="Manajemen Tipe Kamar">
    <div
        x-data="{
            roomTypes: {{ $roomTypes->map(fn ($rt) => [
                'id' => $rt->id,
                'name' => $rt->name,
                'description' => $rt->description,
                'price_per_night' => (int) $rt->price_per_night,
                'capacity' => $rt->capacity,
                'rooms_count' => $rt->rooms_count,
            ])->toJson() }},
            modalOpen: false,
            editingId: null,
            form: { name: '', description: '', price_per_night: '', capacity: '' },
            errors: {},
            saving: false,
            toast: { show: false, message: '', type: 'success' },
            csrf: '{{ csrf_token() }}',
            openCreate() {
                this.editingId = null;
                this.form = { name: '', description: '', price_per_night: '', capacity: '' };
                this.errors = {};
                this.modalOpen = true;
            },
            openEdit(rt) {
                this.editingId = rt.id;
                this.form = {
                    name: rt.name,
                    description: rt.description || '',
                    price_per_night: rt.price_per_night,
                    capacity: rt.capacity,
                };
                this.errors = {};
                this.modalOpen = true;
            },
            async submit() {
                this.saving = true;
                this.errors = {};
                const isEdit = this.editingId !== null;
                const url = isEdit ? `/admin/room-types/${this.editingId}` : '/admin/room-types';
                const data = new FormData();
                data.append('_method', isEdit ? 'PUT' : 'POST');
                data.append('name', this.form.name);
                data.append('description', this.form.description);
                data.append('price_per_night', this.form.price_per_night);
                data.append('capacity', this.form.capacity);
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' },
                        body: data,
                    });
                    const json = await res.json();
                    if (!res.ok) {
                        this.errors = json.errors || {};
                        this.showToast(json.message || 'Terjadi kesalahan.', 'error');
                        return;
                    }
                    if (isEdit) {
                        const idx = this.roomTypes.findIndex(rt => rt.id === this.editingId);
                        this.roomTypes[idx] = { ...json, rooms_count: this.roomTypes[idx].rooms_count };
                    } else {
                        this.roomTypes.push({ ...json, rooms_count: 0 });
                    }
                    this.modalOpen = false;
                    this.showToast(isEdit ? 'Tipe kamar berhasil diperbarui.' : 'Tipe kamar berhasil ditambahkan.', 'success');
                } catch (e) {
                    this.showToast('Gagal menyimpan data.', 'error');
                } finally {
                    this.saving = false;
                }
            },
            async remove(rt) {
                if (!confirm('Hapus tipe kamar ' + rt.name + '?')) return;
                try {
                    const res = await fetch(`/admin/room-types/${rt.id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' },
                    });
                    const json = await res.json();
                    if (!res.ok) {
                        this.showToast(json.message || 'Terjadi kesalahan.', 'error');
                        return;
                    }
                    this.roomTypes = this.roomTypes.filter(r => r.id !== rt.id);
                    this.showToast('Tipe kamar berhasil dihapus.', 'success');
                } catch (e) {
                    this.showToast('Gagal menghapus data.', 'error');
                }
            },
            showToast(message, type) {
                this.toast = { show: true, message, type };
                setTimeout(() => this.toast.show = false, 4000);
            }
        }"
        class="relative"
    >
        <div class="flex items-end justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-ink">Manajemen Tipe Kamar</h1>
                <p class="text-sm text-muted mt-1"><span x-text="roomTypes.length"></span> tipe kamar.</p>
            </div>
            <button @click="openCreate()"
                    class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-primary-light transition">
                + Tambah Tipe Kamar
            </button>
        </div>

        <div x-show="toast.show" x-transition
             class="mb-6 px-4 py-3 rounded-md text-sm flex items-center justify-between"
             :class="toast.type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'">
            <span x-text="toast.message"></span>
            <button @click="toast.show = false" class="font-bold">&times;</button>
        </div>

        <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-surface">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-muted">Nama</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Kapasitas</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Harga / Malam</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Jumlah Kamar</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="rt in roomTypes" :key="rt.id">
                        <tr class="hover:bg-surface/50 transition">
                            <td class="px-4 py-3 font-medium text-ink" x-text="rt.name"></td>
                            <td class="px-4 py-3" x-text="rt.capacity + ' tamu'"></td>
                            <td class="px-4 py-3" x-text="'Rp ' + rt.price_per_night.toLocaleString('id-ID')"></td>
                            <td class="px-4 py-3" x-text="rt.rooms_count"></td>
                            <td class="px-4 py-3 whitespace-nowrap space-x-3">
                                <button type="button" @click="openEdit(rt)" class="text-primary hover:underline">Edit</button>
                                <button type="button" @click="remove(rt)" class="text-danger hover:underline">Hapus</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div x-show="modalOpen" x-transition
             class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40" @click="modalOpen = false"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-ink" x-text="editingId ? 'Edit Tipe Kamar' : 'Tambah Tipe Kamar'"></h2>
                    <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                </div>

                <form @submit.prevent="submit()" class="space-y-4">
                    <div>
                        <label for="m-name" class="block text-sm font-medium text-ink">Nama</label>
                        <input id="m-name" type="text" x-model="form.name" required
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm" />
                        <p class="mt-1 text-xs text-danger" x-show="errors.name" x-text="errors.name[0]"></p>
                    </div>

                    <div>
                        <label for="m-description" class="block text-sm font-medium text-ink">Deskripsi</label>
                        <textarea id="m-description" rows="3" x-model="form.description"
                                  class="mt-1 block w-full rounded-md border-gray-300 text-sm"></textarea>
                        <p class="mt-1 text-xs text-danger" x-show="errors.description" x-text="errors.description[0]"></p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="m-price" class="block text-sm font-medium text-ink">Harga / Malam (Rp)</label>
                            <input id="m-price" type="number" min="0" step="50000" x-model="form.price_per_night" required
                                   class="mt-1 block w-full rounded-md border-gray-300 text-sm" />
                            <p class="mt-1 text-xs text-danger" x-show="errors.price_per_night" x-text="errors.price_per_night[0]"></p>
                        </div>
                        <div>
                            <label for="m-capacity" class="block text-sm font-medium text-ink">Kapasitas</label>
                            <input id="m-capacity" type="number" min="1" x-model="form.capacity" required
                                   class="mt-1 block w-full rounded-md border-gray-300 text-sm" />
                            <p class="mt-1 text-xs text-danger" x-show="errors.capacity" x-text="errors.capacity[0]"></p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="button" @click="modalOpen = false"
                                class="px-4 py-2 border border-gray-300 text-sm font-medium text-muted rounded-md hover:text-ink">
                            Batal
                        </button>
                        <button type="submit" :disabled="saving"
                                class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-primary-light disabled:opacity-50">
                            <span x-text="saving ? 'Menyimpan...' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>