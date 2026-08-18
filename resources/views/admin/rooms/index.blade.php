<x-layouts.admin title="Manajemen Kamar">
    <div class="flex items-end justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-ink">Manajemen Kamar</h1>
            <p class="text-sm text-muted mt-1">{{ $rooms->count() }} kamar terdaftar.</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}"
           class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-primary-light transition">
            + Tambah Kamar
        </a>
    </div>

    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-surface">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-muted">Kamar</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Tipe</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Foto</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($rooms as $room)
                    <tr class="hover:bg-surface/50 transition">
                        <td class="px-4 py-3 font-medium text-ink">{{ $room->room_number }}</td>
                        <td class="px-4 py-3">{{ $room->roomType->name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $room->status === 'available' ? 'bg-green-50 text-success' : 'bg-red-50 text-danger' }}">
                                {{ $room->status === 'available' ? 'Tersedia' : 'Maintenance' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($room->image)
                                <span class="text-muted">Ada</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap space-x-3">
                            <a href="{{ route('admin.rooms.edit', $room) }}" class="text-primary hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" class="inline"
                                  onsubmit="return confirm('Hapus kamar {{ $room->room_number }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.admin>