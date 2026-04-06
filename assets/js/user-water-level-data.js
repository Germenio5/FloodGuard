document.addEventListener('DOMContentLoaded', function() {
    const chartCanvas = document.getElementById('waterLevelChart');
    
    if (typeof waterLevelData !== 'undefined' && waterLevelData && waterLevelData.length > 0) {
        // Sort data by date (oldest first for better visualization)
        waterLevelData.sort((a, b) => new Date(a.date) - new Date(b.date));
        
        // Extract times and heights for chart
        const labels = waterLevelData.map(data => {
            // Extract time from date string (format: "m/d/Y H:i" -> "H:i")
            const parts = data.date.split(' ');
            return parts[1]; // Get the time part
        });
        const heights = waterLevelData.map(data => data.height);
        
        // Get color based on status
        const getStatusColor = (status) => {
            switch(status) {
                case 'normal': return 'rgba(16, 185, 129, 0.7)';
                case 'warning': return 'rgba(255, 209, 71, 0.7)';
                case 'danger': return 'rgba(255, 128, 0, 0.7)';
                case 'critical': return 'rgba(244, 67, 54, 0.7)';
                default: return 'rgba(69, 125, 138, 0.7)';
            }
        };
        
        // Create line chart
        new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Water Level (m)',
                    data: heights,
                    borderColor: '#457d8a',
                    backgroundColor: 'rgba(69, 125, 138, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
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
                                return 'Height: ' + context.parsed.y + ' m';
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
                                return value + ' m';
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
    }
});