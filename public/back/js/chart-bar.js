var ctx = document.getElementById("myAreaChart");

const DATA_COUNT = 7;
const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

const labels = area_chart_labels_monthly;
const data_monthly = {
  labels: labels,
  datasets: [
    {
      label: 'Sales',
      data: area_chart_data_monthly,
      borderColor: "#276740",
      backgroundColor: "#276740",
    }
  ]
};
const data_yearly = {
  labels: area_chart_labels,
  datasets: [
    {
      label: 'Sales',
      data: area_chart_data,
      borderColor: "#276740",
      backgroundColor: "#276740",
    }
  ]
};

function runChart(data, reset = false){
    if(reset){
        chart.destroy();
    }

    chart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              },
              title: {
                display: true,
                text: 'Overview'
              }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                            return '$' + number_format(value);
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + number_format(tooltipItem.yLabel) + ' ' + currency_sign;
                    }
                }
            }
        }
    });
}

runChart(data_monthly);

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

// const actions = [
//     {
//         name: 'Randomize',
//         handler(chart) {
//         chart.data.datasets.forEach(dataset => {
//             dataset.data = Utils.numbers({count: chart.data.labels.length, min: -100, max: 100});
//         });
//         chart.update();
//         }
//     },
//     {
//         name: 'Add Dataset',
//         handler(chart) {
//         const data = chart.data;
//         const dsColor = Utils.namedColor(chart.data.datasets.length);
//         const newDataset = {
//             label: 'Dataset ' + (data.datasets.length + 1),
//             backgroundColor: Utils.transparentize(dsColor, 0.5),
//             borderColor: dsColor,
//             borderWidth: 1,
//             data: Utils.numbers({count: data.labels.length, min: -100, max: 100}),
//         };
//         chart.data.datasets.push(newDataset);
//         chart.update();
//         }
//     },
//     {
//         name: 'Add Data',
//         handler(chart) {
//         const data = chart.data;
//         if (data.datasets.length > 0) {
//             data.labels = Utils.months({count: data.labels.length + 1});

//             for (var index = 0; index < data.datasets.length; ++index) {
//             data.datasets[index].data.push(Utils.rand(-100, 100));
//             }

//             chart.update();
//         }
//         }
//     },
//     {
//         name: 'Remove Dataset',
//         handler(chart) {
//         chart.data.datasets.pop();
//         chart.update();
//         }
//     },
//     {
//         name: 'Remove Data',
//         handler(chart) {
//         chart.data.labels.splice(-1, 1); // remove the label first

//         chart.data.datasets.forEach(dataset => {
//             dataset.data.pop();
//         });

//         chart.update();
//         }
//     }
// ];
