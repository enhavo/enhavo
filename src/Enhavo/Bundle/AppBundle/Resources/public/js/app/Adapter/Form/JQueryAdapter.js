define(["require", "exports", "jquery", "app/Form/Form", "app/Form/Form", "app/Form/FormType", "app/Form/FormType", "app/Form/FormType", "app/Form/FormType", "app/Form/FormType"], function (require, exports, jQuery, Form_1, Form_2, FormType_1, FormType_2, FormType_3, FormType_4, FormType_5) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    (function ($) {
        var listener = new Form_1.FormListener();
        listener.onInsert(function (event) {
            FormType_1.DatePickerType.apply(event.getElement());
            FormType_2.DateTimePickerType.apply(event.getElement());
            FormType_3.CheckboxType.apply(event.getElement());
            FormType_4.SelectType.apply(event.getElement());
        });
        listener.onRelease(function (event) {
            FormType_5.WysiwygType.apply(event.getElement());
        });
        $.fn.form = function () {
            var formInitializer = new Form_2.FormInitializer();
            formInitializer.setElement(this);
            formInitializer.init();
        };
    })(jQuery);
    exports.default = jQuery;
});
