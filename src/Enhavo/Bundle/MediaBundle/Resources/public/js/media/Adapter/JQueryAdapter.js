define(["require", "exports", "media/Media", "jquery"], function (require, exports, Media_1, jQuery) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    (function ($) {
        $.fn.media = function (options) {
            Media_1.Media.apply(this);
        };
    })(jQuery);
    exports.default = jQuery;
});
