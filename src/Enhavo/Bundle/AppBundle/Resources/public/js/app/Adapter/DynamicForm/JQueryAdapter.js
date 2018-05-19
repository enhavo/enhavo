define(["require", "exports", "jquery", "app/app/DynamicForm", "app/app/Form/Form"], function (require, exports, jQuery, DynamicForm_1, Form_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    (function ($) {
        $.fn.dynamicForm = function () {
            DynamicForm_1.DynamicForm.apply(this);
            var listener = new Form_1.FormListener();
            listener.onInsert(function (event) {
                DynamicForm_1.DynamicForm.apply(event.getElement());
            });
        };
    })(jQuery);
    exports.default = jQuery;
});
