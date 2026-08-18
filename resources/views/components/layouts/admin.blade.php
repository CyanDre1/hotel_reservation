<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' — ' . config('app.name') : config('app.name') }}</title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink antialiased bg-surface">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">
            <aside class="lg:w-64 lg:shrink-0 lg:fixed lg:inset-y-0 lg:left-0">
                <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/40 lg:hidden"></div>

                <div class="fixed inset-y-0 left-0 z-30 w-64 bg-primary text-white flex flex-col transition-transform duration-200 lg:translate-x-0"
                     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                    <div class="flex items-center justify-between px-5 h-16 border-b border-white/10">
                        <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-tight">
                            {{ config('app.name') }}
                        </a>
                        <button @click="sidebarOpen = false" class="lg:hidden text-white/70 hover:text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                        <x-admin-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-admin-nav-link>
                        <x-admin-nav-link :href="route('admin.rooms.index')" :active="request()->routeIs('admin.rooms.*')">
                            Manajemen Kamar
                        </x-admin-nav-link>
                        <x-admin-nav-link :href="route('admin.room-types.index')" :active="request()->routeIs('admin.room-types.*')">
                            Manajemen Tipe Kamar
                        </x-admin-nav-link>
                        <x-admin-nav-link :href="route('admin.bookings.index')" :active="request()->routeIs('admin.bookings.*')">
                            Manajemen Booking
                        </x-admin-nav-link>
                        <x-admin-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            Manajemen User
                        </x-admin-nav-link>
                        <x-admin-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            Laporan
                        </x-admin-nav-link>
                    </nav>
                </div>
            </aside>

            <div class="flex-1 min-w-0 lg:ps-64">
                <header class="sticky top-0 z-10 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6">
                        <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="ms-auto flex items-center gap-4">
                            <a href="{{ route('home') }}" class="text-sm text-muted hover:text-ink">
                                Lihat Situs
                            </a>

                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <span class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-semibold uppercase">
                                            {{ Str::substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                        <span class="hidden sm:block ms-2">{{ Auth::user()->name }}</span>
                                        <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

                <main class="px-4 sm:px-6 py-6">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                             x-transition
                             class="mb-6 flex items-center justify-between px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-800 text-sm">
                            <span>{{ session('success') }}</span>
                            <button @click="show = false" class="text-green-600 hover:text-green-800">&times;</button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                             x-transition
                             class="mb-6 flex items-center justify-between px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-800 text-sm">
                            <span>{{ session('error') }}</span>
                            <button @click="show = false" class="text-red-600 hover:text-red-800">&times;</button>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
