// initializes the water level chart on user dashboard
// expects a <script id="chart-data" type="application/json"> element containing
// the JSON object with "labels" and "heights" arrays

document.addEventListener('DOMContentLoaded', function() {
    const script = document.getElementById('chart-data');
    let chartData = { labels: [], heights: [] };
    if (script) {
        try {
            chartData = JSON.parse(script.textContent);
        } catch (e) {
            console.error('Invalid chart data JSON', e);
        }
    }

    const ctx = document.getElementById('waterLevelChart').getContext('2d');
    const hasData = chartData.labels && chartData.labels.length > 0 && chartData.labels[0] !== 'No Data';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Water Level (m)',
                data: chartData.heights,
                borderColor: '#457d8a',
                backgroundColor: 'rgba(69, 125, 138, 0.05)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: hasData ? 5 : 0,
                pointBackgroundColor: '#457d8a',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: true, position: 'top' },
                title: { display: false }
            },
            scales: {
                y: { beginAtZero: false, title: { display: true, text: 'Level (m)' } },
                x: { title: { display: true, text: 'Time' } }
            }
        }
    });
});