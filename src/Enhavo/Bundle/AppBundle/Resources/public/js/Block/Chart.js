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
      new Chartjs(ctx, data);
    };
  }

  var chart = new Chart();
  chart.init();
  return chart;
});