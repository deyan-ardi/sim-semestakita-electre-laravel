var options = {
  chart: {
    height: 200,
    type: 'line',
    zoom: {
      enabled: false
    },
    toolbar: {
      show: false
    },
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    curve: 'smooth',
    width: 5,
  },
  series: [{
    name: "Penyetoran",
    data: penyetoran
  }],
  grid: {
    borderColor: '#e0e6ed',
    strokeDashArray: 5,
    xaxis: {
      lines: {
        show: true
      }
    },   
    yaxis: {
      lines: {
        show: false,
      } 
    },
    padding: {
      top: 0,
      right: 0,
      bottom: 0,
      left: 10
    }, 
  },
  xaxis: {
    categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
  },
  yaxis: {
    show: false,
  },
  theme: {
    monochrome: {
      enabled: true,
      color: '#006e2e',
      shadeIntensity: 0.1
    },
  },
  fill: {
    type: 'solid',
  },
  markers: {
    size: 0,
    opacity: 0.2,
    colors: ["#006e2e"],
    strokeColor: "#fff",
    strokeWidth: 2,
    hover: {
      size: 7,
    }
  },
}

var chart = new ApexCharts(
  document.querySelector("#visitorsGraph"),
  options
);

chart.render();