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
    <body class="font-sans text-ink antialiased bg-white">
        <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-gray-200">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-lg font-bold text-primary tracking-tight">
                    {{ config('app.name') }}
                </a>

                <div class="flex items-center gap-4">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('dashboard') }}" class="text-sm text-muted hover:text-ink">Dashboard</a>
                        @endif
                        <a href="{{ route('bookings.index') }}" class="text-sm text-muted hover:text-ink">Booking Saya</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-muted hover:text-ink">Log Out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-muted hover:text-ink">Login</a>
                        <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-primary hover:bg-primary-light px-4 py-2 rounded-md">
                            Daftar
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
            @if (session('success'))
                <div class="mb-6 px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>

        <footer class="border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-muted">
                <div>
                    <p class="font-semibold text-ink">{{ config('app.name') }}</p>
                    <p class="mt-1">Jl. Contoh No. 1, Jakarta — Telp: (021) 1234-5678</p>
                </div>
                <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
