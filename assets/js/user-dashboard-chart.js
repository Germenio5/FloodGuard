// initializes the water level chart on user dashboard with enhanced styling
// expects a <script id="chart-data" type="application/json"> element containing
// the JSON object with "labels" and "heights" arrays

document.addEventListener('DOMContentLoaded', function() {
    const script = document.getElementById('chart-data');
    let chartData = { labels: [], heights: [], area: '', location: '' };
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
                backgroundColor: 'rgba(69, 125, 138, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: hasData ? 5 : 0,
                pointBackgroundColor: '#457d8a',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#3d6b77'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: '600'
                        },
                        color: '#333',
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const value = Number(context.parsed.y);
                            return 'Height: ' + (isNaN(value) ? context.parsed.y : value.toFixed(2)) + ' m';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Water Level (meters)'
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#666',
                        callback: function(value) {
                            const numericValue = Number(value);
                            return (isNaN(numericValue) ? value : numericValue.toFixed(2)) + ' m';
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#666',
                        maxRotation: 45,
                        minRotation: 0
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });
});