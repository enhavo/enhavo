import { Grid } from 'grid/Grid';
import * as jQuery from 'jquery'

(function ($) {
    $.fn.grid = function ()
    {
        Grid.apply(this);
    }
})(jQuery);


export default jQuery;