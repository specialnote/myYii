$(function () {

    $.getJSON(day_rate_data_url, function (data) {

        // Create a timer
        var start = +new Date();
        // Create the chart
        $('#day-rate-container').highcharts('StockChart', {
            chart: {
                events: {
                    load: function () {

                    }
                }

            },
            rangeSelector: {
                buttons: []
            },
            yAxis: {
                title: {
                    text: 'ate'
                }
            },
            series: [{
                name: 'Temperature',
                data: data
            }]

        });
    });
});