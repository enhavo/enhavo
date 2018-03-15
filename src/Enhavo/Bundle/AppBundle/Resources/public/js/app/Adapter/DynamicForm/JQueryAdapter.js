define(["require", "exports", "jquery", "app/app/DynamicForm"], function (require, exports, jQuery, DynamicForm_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    (function ($) {
        $.fn.dynamicForm = function () {
            DynamicForm_1.DynamicForm.apply(this);
        };
    })(jQuery);
    exports.default = jQuery;
});
