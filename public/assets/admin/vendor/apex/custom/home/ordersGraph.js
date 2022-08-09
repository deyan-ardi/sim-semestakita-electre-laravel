var options = {
	chart: {
		height: 250,
		type: 'radialBar',
		toolbar: {
			show: false,
		},
	},
	plotOptions: {
		radialBar: {
			dataLabels: {
				name: {
					fontSize: '12px',
					fontColor: 'black',
				},
				value: {
					fontSize: '21px',
				},
				total: {
					show: true,
					label: 'Sampah Harian',
					formatter: function (w) {
						// By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
						return total_harian
					}
				}
			},
			track: {
        show: true,
        margin: 7, 
    	},
		}
	},
	series: [persen_masuk, persen_keluar],
	labels: ['Sampah Masuk', 'Sampah Keluar'],
	colors: ['#006e2e', '#f16a5d'],
}

var chart = new ApexCharts(
	document.querySelector("#ordersGraph"),
	options
);
chart.render();