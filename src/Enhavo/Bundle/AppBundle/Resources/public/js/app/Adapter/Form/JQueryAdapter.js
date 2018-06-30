define(["require", "exports", "jquery", "app/Form/Form", "app/Form/FormType"], function (require, exports, jQuery, Form_1, FormType_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    (function ($) {
        Form_1.FormListener.onInsert(function (event) {
            FormType_1.DatePickerType.apply(event.getElement());
            FormType_1.DateTimePickerType.apply(event.getElement());
            FormType_1.CheckboxType.apply(event.getElement());
            FormType_1.SelectType.apply(event.getElement());
            FormType_1.ListType.apply(event.getElement());
        });
        Form_1.FormListener.onRelease(function (event) {
            FormType_1.WysiwygType.apply(event.getElement());
        });
        $.fn.form = function () {
            var formInitializer = new Form_1.FormInitializer();
            formInitializer.setElement(this);
            formInitializer.init();
        };
    })(jQuery);
    exports.default = jQuery;
});
