<x-layouts.admin title="Manajemen User">
    <div class="flex items-end justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-ink">Manajemen User</h1>
            <p class="text-sm text-muted mt-1">{{ $users->count() }} user terdaftar.</p>
        </div>
    </div>

    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-surface">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-muted">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Email</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Role</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Terdaftar</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($users as $user)
                    <tr class="hover:bg-surface/50 transition">
                        <td class="px-4 py-3 font-medium text-ink">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-primary/10 text-primary' : 'bg-surface text-muted border border-gray-200' }}">
                                {{ $user->role === 'admin' ? 'Admin' : 'Guest' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-muted">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap space-x-3">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-primary hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                  onsubmit="return confirm('Hapus user {{ $user->name }}?')">
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