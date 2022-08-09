var options = {
    chart: {
        height: 250,
        type: "bar",
        toolbar: {
            show: false,
        },
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: "45px",
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        show: true,
        width: 2,
        colors: ["transparent"],
    },
    series: [
        {
            name: "Total Sampah",
            data: B3_jml,
        },
    ],
    legend: {
        show: false,
    },
    xaxis: {
        categories: B3_name,
    },
    yaxis: {
        show: false,
    },
    fill: {
        opacity: 1,
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val + " Kg";
            },
        },
    },
    grid: {
        borderColor: "#e0e6ed",
        strokeDashArray: 5,
        xaxis: {
            lines: {
                show: true,
            },
        },
        yaxis: {
            lines: {
                show: false,
            },
        },
        padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
        },
    },
    colors: ["#f16a5d"],
};
var chart = new ApexCharts(
  document.querySelector("#earningsGraphB3"),
  options
);
chart.render();