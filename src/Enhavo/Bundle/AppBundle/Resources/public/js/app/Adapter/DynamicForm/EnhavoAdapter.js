define(["require", "exports", "app/app/DynamicForm", "app/app/Form/Form", "app/Form", "media/Media"], function (require, exports, DynamicForm_1, Form_1, form, Media_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var EnhavoAdapter = /** @class */ (function () {
        function EnhavoAdapter() {
            EnhavoAdapter.initFormListener();
        }
        EnhavoAdapter.initFormListener = function () {
            $(document).on('formOpenAfter', function (event, element) {
                DynamicForm_1.DynamicForm.apply(element);
            });
            $(document).on('gridAddAfter', function (event, element) {
                //DynamicForm.apply(element);
            });
            var listener = new Form_1.FormListener();
            listener.onRelease(function (event) {
                // DynamicForm.apply();
                form.initWysiwyg(event.getElement());
                form.initRadioAndCheckbox(event.getElement());
                form.initSelect(event.getElement());
                form.initDataPicker(event.getElement());
                form.initList(event.getElement());
                Media_1.Media.apply(event.getElement());
            });
        };
        return EnhavoAdapter;
    }());
    var adapter = new EnhavoAdapter();
    exports.default = adapter;
});
