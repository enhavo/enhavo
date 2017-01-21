define([], function() {

  function Chart() {
    this.init = function(chart) {
      chart.options = {
        scales: {
          yAxes: [{
            ticks: {
              callback: function(value, index, values) {
                return value.toLocaleString("en-US",{style:"currency", currency:"EUR"});
              }
            }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItems, data) {
              return tooltipItems.yLabel + ' €';
            }
          }
        }
      };
    }
  }

  return new Chart();
});