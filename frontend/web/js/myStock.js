$(function () {

    $.getJSON(day_rate_data_url, function (data) {

        // Create a timer
        var start = +new Date();
        // Create the chart
        $('#day-rate-container').highcharts('StockChart', {
            chart: {
                events: {
                    load: function () {
                        if (!window.isComparing) {
                            this.setTitle(null, {
                                text: 'Built chart in ' + (new Date() - start) + 'ms'
                            });
                        }
                    }
                },
                zoomType: 'x'
            },
            rangeSelector: {

                buttons: [{
                    type: 'all',
                    text: 'All'
                }],
                selected: 3
            },

            yAxis: {
                title: {
                    text: 'Temperature (°C)'
                }
            },

            title: {
                text: 'Hourly temperatures in Vik i Sogn, Norway, 2004-2010'
            },

            subtitle: {
                text: 'Built chart in ...' // dummy text to reserve space for dynamic subtitle
            },

            series: [{
                name: 'Temperature',
                data: data
            }]

        });
    });
});