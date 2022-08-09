var options = {
    chart: {
        height: 450,
        type: "radialBar",
        toolbar: {
            show: false,
        },
    },
    plotOptions: {
        radialBar: {
            dataLabels: {
                name: {
                    fontSize: "12px",
                    fontColor: "black",
                },
                value: {
                    fontSize: "21px",
                },
                total: {
                    show: true,
                    label: "Total Sampah di TPST",
                    formatter: function (w) {
                        // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                        return total_sampah + " Kg";
                    },
                },
            },
            track: {
                show: true,
                margin: 7,
            },
        },
    },
    series: [
        jml_akhir_organik,
        jml_akhir_nonorganik,
        jml_akhir_B3,
        jml_akhir_residu,
    ],
    labels: [
        "Sampah Organik",
        "Sampah Anorganik",
        "Sampah B3",
        "Sampah Residu",
    ],
    colors: ["#006e2e", "#e4b42b", "#f16a5d", "#394758"],
};

var chart = new ApexCharts(
	document.querySelector("#ordersGraph"),
	options
);
chart.render();