define(["require", "exports", "app/app/DynamicForm"], function (require, exports, DynamicForm_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var EnhavoAdapter = /** @class */ (function () {
        function EnhavoAdapter() {
            EnhavoAdapter.initFormListener();
        }
        EnhavoAdapter.initFormListener = function () {
            $(document).on('formOpenAfter', function (event, element) {
                var config = new DynamicForm_1.DynamicFormConfig();
                config.scope = $(element).offsetParent().get(0);
                DynamicForm_1.DynamicForm.apply(element);
            });
            $(document).on('gridAddAfter', function (event, element) {
                DynamicForm_1.DynamicForm.apply(element);
            });
        };
        return EnhavoAdapter;
    }());
    var adapter = new EnhavoAdapter();
    exports.default = adapter;
});
