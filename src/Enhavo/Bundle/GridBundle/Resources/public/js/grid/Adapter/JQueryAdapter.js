define(["require", "exports", "grid/Grid", "jquery"], function (require, exports, Grid_1, jQuery) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    (function ($) {
        $.fn.grid = function () {
            Grid_1.Grid.apply(this);
        };
    })(jQuery);
    exports.default = jQuery;
});
