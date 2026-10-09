fetch('dashboardQuery/get_top_products.php')
    .then(response => response.json())
    .then(chartData => {
        console.log("Chart Data:", chartData);

        // Dashboard Analytics appearance:
        Highcharts.chart('container', {
            chart: {
                type: 'column',
                backgroundColor: '#ffffff'
            },

            // Black text is used because the Analytics chart background is white.
            title: { 
                text: 'Analytics',
                style: {
                    color: '#000000',
                    fontSize: '20px',
                    fontWeight: 'bold'
                }
            },

            xAxis: {
                categories: chartData.categories,
                crosshair: true,
                labels: {
                    style: {
                        color: '#000000'
                    }
                }
            },

            yAxis: {
                min: 0,
                title: { 
                    text: 'Units Sold',
                    style: {
                        color: '#000000',
                        fontSize: '14px'
                    }
                },
                labels: {
                    style: {
                        color: '#000000'
                    }
                }
            },

            tooltip: {
                valueSuffix: ' units'
            },

            legend: {
                itemStyle: {
                    color: '#000000',
                    fontSize: '16px',
                    fontWeight: 'bold'
                }
            },
            
            // Keep colorByPoint: true so the bars remain individually colored.
            plotOptions: {
                column: { 
                    pointPadding: 0.2, 
                    borderWidth: 0,
                    colorByPoint: true
                }
            },

            series: [{
                name: 'Top Selling Products',
                data: chartData.data
            }]
        });
    })
    .catch(err => console.error("Loading Chart error:", err));