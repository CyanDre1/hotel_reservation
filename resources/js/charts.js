import Chart from 'chart.js/auto';

const bookingsCanvas = document.getElementById('bookingsChart');
if (bookingsCanvas) {
    new Chart(bookingsCanvas, {
        type: 'bar',
        data: {
            labels: JSON.parse(bookingsCanvas.dataset.labels),
            datasets: [{
                label: 'Jumlah Booking',
                data: JSON.parse(bookingsCanvas.dataset.values),
                backgroundColor: '#0F3D3E',
                borderRadius: 4,
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
            },
        },
    });
}

const revenueCanvas = document.getElementById('revenueChart');
if (revenueCanvas) {
    new Chart(revenueCanvas, {
        type: 'line',
        data: {
            labels: JSON.parse(revenueCanvas.dataset.labels),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: JSON.parse(revenueCanvas.dataset.values),
                borderColor: '#22C55E',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.3,
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: { beginAtZero: true },
            },
        },
    });
}