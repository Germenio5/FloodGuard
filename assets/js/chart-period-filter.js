// Handles period filter changes on user dashboard
// Updates chart data via AJAX without page reload

document.addEventListener('DOMContentLoaded', function() {
    const periodFilter = document.getElementById('chartPeriodFilter');

    // Get chart instance from canvas element
    const getChartInstance = () => {
        const canvasElement = document.getElementById('waterLevelChart');
        if (!canvasElement) return null;
        
        // First try the stored property
        if (canvasElement.waterLevelChart) {
            return canvasElement.waterLevelChart;
        }
        
        // Then try the global window variable
        if (window.waterLevelChartInstance) {
            return window.waterLevelChartInstance;
        }
        
        return null;
    };

    // Add event listener to period filter dropdown
    if (periodFilter) {
        periodFilter.addEventListener('change', function() {
            const selectedPeriod = this.value;
            
            // Get area name from the chart data
            const chartDataScript = document.getElementById('chart-data');
            let areaName = '';
            if (chartDataScript) {
                try {
                    const data = JSON.parse(chartDataScript.textContent);
                    areaName = data.area || '';
                } catch (e) {
                    console.error('Error parsing chart data', e);
                }
            }

            // Make AJAX call to get chart data
            fetch(`../controllers/get-chart-data-ajax.php?chart_period=${selectedPeriod}&area_name=${encodeURIComponent(areaName)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    // Update chart data
                    updateChart(data.data);
                    
                    // Scroll to chart smoothly
                    setTimeout(() => {
                        const graphBox = document.querySelector('.graph-box');
                        if (graphBox) {
                            graphBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }, 300);
                } else {
                    console.error('Failed to fetch chart data:', data);
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                alert('Failed to load chart data. Please try again.');
            });
        });
    }

    // Function to update chart with new data
    function updateChart(chartData) {
        let chart = getChartInstance();
        
        if (!chart) {
            console.error('Chart instance not found. Waiting...');
            // Retry after a short delay
            setTimeout(() => {
                chart = getChartInstance();
                if (chart) {
                    performChartUpdate(chart, chartData);
                } else {
                    console.error('Failed to get chart instance after retry');
                }
            }, 500);
        } else {
            performChartUpdate(chart, chartData);
        }
    }

    // Perform the actual chart update
    function performChartUpdate(chart, chartData) {
        if (!chart) return;

        // Update chart data
        chart.data.labels = chartData.labels;
        chart.data.datasets[0].data = chartData.heights;
        
        // Update the title dynamically
        const periodTitles = {
            'daily': 'Last 24 Hours',
            'weekly': 'Last 7 Days',
            'monthly': 'Last 30 Days'
        };
        
        const graphTitle = document.querySelector('.graph-title h3');
        if (graphTitle) {
            graphTitle.textContent = `Water Level History - ${periodTitles[chartData.period] || 'History'}`;
        }

        // Refresh the chart
        chart.update('none'); // Use 'none' to avoid animation, or use other options like 'active'
    }
});
