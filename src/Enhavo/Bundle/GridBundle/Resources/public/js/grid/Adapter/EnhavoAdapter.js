define(["require", "exports", "media/Media", "grid/Grid", "app/Form"], function (require, exports, Media_1, Grid_1, form) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var EnhavoAdapter = /** @class */ (function () {
        function EnhavoAdapter() {
            EnhavoAdapter.initFormListener();
        }
        EnhavoAdapter.initFormListener = function () {
            $(document).on('formOpenAfter', function (event, element) {
                var config = new Grid_1.GridConfig();
                config.scope = $(element).offsetParent().get(0);
                Grid_1.Grid.apply(element);
                Media_1.Media.apply(element);
            });
            $(document).on('gridAddAfter', function (event, element) {
                Media_1.Media.apply(element);
                form.initWysiwyg(element);
                form.initRadioAndCheckbox(element);
                form.initSelect(element);
                form.initDataPicker(element);
                form.initList(element);
            });
        };
        return EnhavoAdapter;
    }());
    var adapter = new EnhavoAdapter();
    exports.default = adapter;
});
