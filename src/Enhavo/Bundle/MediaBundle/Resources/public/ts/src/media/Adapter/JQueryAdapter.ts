import { Media } from 'media/Media';
import * as jQuery from 'jquery'

(function ($) {
    $.fn.media = function (options:JQueryMediaOptions)
    {
        Media.apply(this);
    }
})(jQuery);

export default jQuery;