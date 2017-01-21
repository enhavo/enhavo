define(['jquery', 'app/Admin', 'chartjs'], function($, admin, Chartjs) {

  function Chart() {
    var self = this;

    this.init = function () {
      $('body').on('initBlock', function (event, data) {
        if (data.type == 'chart') {
          self.initChart(data.block)
        }
      });
    };

    this.initChart = function(block) {
      var $block = $(block);
      var ctx = $block.find('canvas').get(0);
      var data = $(block).data('block-chart-data');
      var options = $(block).data('block-chart-options');
      var type = $(block).data('block-chart-type');
      var app = $(block).data('block-chart-app');

      var chart = {
        type: type,
        options: options == null ? {} : options,
        data: data
      };

      if(app) {
        require([app], function(app) {
          app.init(chart);
          new Chartjs(ctx, chart);
        })
      } else {
        new Chartjs(ctx, chart);
      }
    };
  }

  var chart = new Chart();
  chart.init();
  return chart;
});