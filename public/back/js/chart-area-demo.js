// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Area Chart Example
var ctx = document.getElementById("myAreaChart");

var data_yearly = {
    labels: area_chart_labels,
    datasets: [{
        fill:'start',
        label: "Amount",
        lineTension: 0.3,
        backgroundColor: "rgba(252, 99, 132, .3)",
        borderColor: "#FF6384",
        pointRadius: 3,
        pointBackgroundColor: "rgba(252, 99, 132, .3)",
        pointBorderColor: "#FF6384",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(252, 99, 132, .3)",
        pointHoverBorderColor: "#FF6384",
        pointHitRadius: 10,
        pointBorderWidth: 4,
        data: area_chart_data,
    }],
};

var data_monthly = {
    labels: area_chart_labels_monthly,
    datasets: [{
        fill:'start',
        label: "Amount",
        lineTension: 0.3,
        backgroundColor: "rgba(252, 99, 132, .3)",
        borderColor: "#FF6384",
        pointRadius: 3,
        pointBackgroundColor: "rgba(252, 99, 132, .3)",
        pointBorderColor: "#FF6384",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(252, 99, 132, .3)",
        pointHoverBorderColor: "#FF6384",
        pointHitRadius: 10,
        pointBorderWidth: 4,
        data: area_chart_data_monthly,
    }],
};

function runChart(data, reset = false){
    if(reset){
        forecast_chart.destroy();
    }

    forecast_chart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
          maintainAspectRatio: false,
          layout: {
            padding: {
              left: 10,
              right: 25,
              top: 25,
              bottom: 0
            }
          },
          scales: {
              xAxes: [{
                  time: {
                    unit: 'date'
                  },
              }],
              yAxes: [{
                ticks: {
                    callback: function(value, index, values) {
                        return number_format(value) + ' ' + currency_sign;
                    }
                },
                gridLines: {
                    color: "rgb(234, 236, 244)",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
              }],
          },
          legend: {
            display: false
          },
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
            callbacks: {
              label: function(tooltipItem, chart) {
                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                return datasetLabel + ': ' + currency_sign + number_format(tooltipItem.yLabel);
              }
            }
          }
        }
    });
}

runChart(data_monthly);


$(document).on('click', '.chart_type', function(){
    let this_data_type = $(this).data('type');

    if(this_data_type == 'yearly'){
        runChart(data_yearly, true);

        $(this).closest('.dropdown').find('button').html('Yearly');
    }else{
        runChart(data_monthly, true);

        $(this).closest('.dropdown').find('button').html('Monthly');
    }
});
