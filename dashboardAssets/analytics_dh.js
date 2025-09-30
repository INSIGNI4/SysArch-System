fetch('dashboardQuery/get_top_products.php')
    .then(response => response.json())
    .then(chartData => {
        console.log("Chart Data:", chartData);
        Highcharts.chart('container', {
            chart: { type: 'column' },
            title: { 
                text: 'Analytics',
                style: {
                    fontSize: '20px',   // Title font size
                    fontWeight: 'bold'
                }
            },
            xAxis: {
                categories: chartData.categories,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: { 
                    text: 'Units Sold',
                    style: {
                        fontSize: '14px'  // Y-axis title font size
                    }
                }
            },
            tooltip: { valueSuffix: ' units' },
            legend: {
                itemStyle: {
                    fontSize: '16px',   // 🔹 Font size of "Top Selling Products"
                    fontWeight: 'bold'
                }
            },
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
