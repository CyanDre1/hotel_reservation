<x-layouts.admin title="Laporan">
    <div class="flex items-end justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-ink">Laporan</h1>
            <p class="text-sm text-muted mt-1">Ringkasan 6 bulan terakhir.</p>
        </div>
        <span class="text-sm text-muted">Occupancy Rate: <span class="font-semibold text-primary">{{ $occupancyRate }}%</span></span>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="font-semibold text-ink mb-4">Booking per Bulan</h2>
            <canvas id="bookingsChart"
                    data-labels="{{ json_encode($monthLabels) }}"
                    data-values="{{ json_encode($bookingCounts) }}"></canvas>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="font-semibold text-ink mb-4">Pendapatan per Bulan</h2>
            <canvas id="revenueChart"
                    data-labels="{{ json_encode($monthLabels) }}"
                    data-values="{{ json_encode($revenues) }}"></canvas>
        </div>
    </div>

    @vite(['resources/js/charts.js'])
</x-layouts.admin>