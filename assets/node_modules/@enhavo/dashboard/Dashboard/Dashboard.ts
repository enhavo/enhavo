import * as $ from 'jquery'
import * as Chartjs from 'chart.js'
export default class Dashboard
{
    static initChart(block: HTMLElement)
    {
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

        new Chartjs(ctx, chart);
    }
}