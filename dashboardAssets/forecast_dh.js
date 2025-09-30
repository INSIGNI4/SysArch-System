document.addEventListener('DOMContentLoaded', function () {
    renderForecastChart();
});

function renderForecastChart() {
    const allWeeks = ['Week1','Week2','Week3','Week4','Week5','Week6','Week7','Week8'];
    const allForecast = [0,0,0,1080,1280,1230,1000,1400];
    const allActual = [1000,1200,1100,1300,1250,1400,1050,1450];

    let startIndex = 0;
    const visibleCount = 5;

    const chart = Highcharts.chart('container1', {
        chart: { scrollablePlotArea: { minWidth: 700 } },
        title: { text: 'Forecast', align: 'center', style: { fontSize: '20px', fontWeight: 'bold' } },
        subtitle: { text: 'Weekly', align: 'left', style: { fontSize: '16px' } },
        xAxis: { categories: allWeeks.slice(startIndex, startIndex + visibleCount), tickWidth: 0, gridLineWidth: 1 },
        yAxis: [{ title: { text: null }, labels: { align: 'left', x: 3, y: 16, style: { fontSize: '14px' } } }],
        legend: { align: 'left', verticalAlign: 'top', borderWidth: 0, itemStyle: { fontSize: '14px' } },
        tooltip: { shared: true, crosshairs: true },
        plotOptions: { series: { cursor: 'pointer', className: 'popup-on-click', marker: { lineWidth: 1 } } },
        series: [
            { name: 'Forecast', data: allForecast.slice(startIndex, startIndex + visibleCount), lineWidth: 4, marker: { radius: 4 } },
            { name: 'Actual', data: allActual.slice(startIndex, startIndex + visibleCount) }
        ],
        chart: {
            events: {
                render: function () {
                    const chart = this;
                    const xAxis = chart.xAxis[0];
                    const y = chart.plotTop + chart.plotHeight + 10;
                    const sideOffset = 40;

                    if (!chart.prevButton) {
                        chart.prevButton = chart.renderer.button('<', 0, y).attr({ zIndex: 5 }).add().on('click', function () {
                            if (startIndex > 0) {
                                startIndex--;
                                updateChart();
                            }
                        });
                    }

                    if (!chart.nextButton) {
                        chart.nextButton = chart.renderer.button('>', 0, y).attr({ zIndex: 5 }).add().on('click', function () {
                            if (startIndex + visibleCount < allWeeks.length) {
                                startIndex++;
                                updateChart();
                            }
                        });
                    }

                    const xPrev = xAxis.toPixels(startIndex, true) - sideOffset;
                    const xNext = xAxis.toPixels(startIndex + visibleCount - 1, true) + sideOffset;

                    chart.prevButton.attr({ x: xPrev, y: y });
                    chart.nextButton.attr({ x: xNext, y: y });

                    function updateChart() {
                        chart.xAxis[0].setCategories(allWeeks.slice(startIndex, startIndex + visibleCount));
                        chart.series[0].setData(allForecast.slice(startIndex, startIndex + visibleCount));
                        chart.series[1].setData(allActual.slice(startIndex, startIndex + visibleCount));
                        chart.redraw();
                    }
                }
            }
        }
    });

    Highcharts.addEvent(Highcharts.Point, 'click', function () {
        if (this.series.options.className?.includes('popup-on-click')) {
            const date = chart.time.dateFormat('%A, %b %e, %Y', this.x);
            const text = `<b>${date}</b><br/>${this.y} ${this.series.name}`;
            const anchorX = this.plotX + this.series.xAxis.pos;
            const anchorY = this.plotY + this.series.yAxis.pos;
            const align = anchorX < chart.chartWidth - 200 ? 'left' : 'right';
            const x = align === 'left' ? anchorX + 10 : anchorX - 10;
            const y = anchorY - 30;

            if (!chart.sticky) {
                chart.sticky = chart.renderer.label(text, x, y, 'callout', anchorX, anchorY)
                    .attr({ align, fill: 'rgba(0,0,0,0.75)', padding: 10, zIndex: 7 })
                    .css({ color: 'white', fontSize: '14px' })
                    .on('click', function () { chart.sticky = chart.sticky.destroy(); })
                    .add();
            } else {
                chart.sticky.attr({ align, text }).animate({ anchorX, anchorY, x, y }, { duration: 250 });
            }
        }
    });
}
