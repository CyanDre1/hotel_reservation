<x-layouts.admin title="Dashboard">
    <h1 class="text-2xl font-bold text-ink mb-6">Dashboard</h1>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <p class="text-sm text-muted">Total Booking</p>
            <p class="mt-2 text-3xl font-bold text-ink">{{ number_format($totalBookings) }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <p class="text-sm text-muted">Booking Pending</p>
            <p class="mt-2 text-3xl font-bold text-warning">{{ number_format($pendingBookings) }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <p class="text-sm text-muted">Kamar Tersedia</p>
            <p class="mt-2 text-3xl font-bold text-primary">{{ $availableRooms }} <span class="text-sm text-muted font-normal">/ {{ $totalRooms }}</span></p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <p class="text-sm text-muted">Revenue Bulan Ini</p>
            <p class="mt-2 text-3xl font-bold text-success">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="mt-6 bg-white border border-gray-200 rounded-lg p-6">
        <p class="text-sm text-muted">Total User Terdaftar</p>
        <p class="mt-2 text-3xl font-bold text-ink">{{ number_format($totalUsers) }}</p>
    </div>
</x-layouts.admin>