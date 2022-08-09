var options = {
    chart: {
        height: 280,
        type: "area",
        toolbar: {
            show: false,
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        curve: "smooth",
        width: 3,
    },
    series: [
        {
            name: "Tunggakan",
            data: tunggakan,
        },
        {
            name: "Pelunasan",
            data: pelunasan,
        },
    ],
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
            bottom: 10,
            left: 0,
        },
    },
    xaxis: {
        categories: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ],
    },
    yaxis: {
        labels: {
            show: false,
        },
    },
    colors: ["#006e2e", "#47a318"],
    markers: {
        size: 0,
        opacity: 0.3,
        colors: ["#006e2e"],
        strokeColor: "#ffffff",
        strokeWidth: 2,
        hover: {
            size: 7,
        },
    },
};

var chart = new ApexCharts(
	document.querySelector("#salesGraph"),
	options
);

chart.render();
