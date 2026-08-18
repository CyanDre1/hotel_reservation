<x-layouts.admin title="Edit User">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-ink">Edit User</h1>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-muted hover:text-ink">&larr; Kembali</a>
    </div>

    <div class="max-w-xl bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="name" :value="__('Nama')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                              value="{{ old('name', $user->name) }}" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                              value="{{ old('email', $user->email) }}" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="role" :value="__('Role')" />
                <select id="role" name="role" required
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                    <option value="guest" @selected(old('role', $user->role) === 'guest')>Guest</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div class="flex justify-end border-t border-gray-100 pt-4">
                <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-layouts.admin>