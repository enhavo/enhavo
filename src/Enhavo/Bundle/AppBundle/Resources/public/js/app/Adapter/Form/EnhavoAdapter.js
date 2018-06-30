define(["require", "exports", "app/Form/Form", "app/Form/Form"], function (require, exports, Form_1, Form_2) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var EnhavoAdapter = /** @class */ (function () {
        function EnhavoAdapter() {
            EnhavoAdapter.initFormListener();
        }
        EnhavoAdapter.initFormListener = function () {
            Form_1.FormListener.onInsert(function (event) {
                Form_2.DatePicker.apply(event.getElement());
            });
        };
        return EnhavoAdapter;
    }());
    var adapter = new EnhavoAdapter();
    exports.default = adapter;
});
