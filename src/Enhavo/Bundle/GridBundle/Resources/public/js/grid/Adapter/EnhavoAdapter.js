define(["require", "exports", "media/Media", "media/Adapter/EnhavoAdapter", "grid/Grid", "app/Form"], function (require, exports, Media_1, EnhavoAdapter_1, Grid_1, form) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var EnhavoAdapter = /** @class */ (function () {
        function EnhavoAdapter() {
            EnhavoAdapter.initFormListener();
        }
        EnhavoAdapter.initFormListener = function () {
            $(document).on('formOpenAfter', function (event, element) {
                Grid_1.Grid.apply(element);
            });
            $(document).on('gridAddAfter', function (event, element) {
                EnhavoAdapter_1.enhavoAdapter.initMedia(element);
                form.initWysiwyg(element);
                form.initRadioAndCheckbox(element);
                form.initSelect(element);
                form.initDataPicker(element);
                form.initList(element);
            });
        };
        EnhavoAdapter.prototype.initMedia = function (form) {
            Media_1.Media.apply(form);
        };
        return EnhavoAdapter;
    }());
    var adapter = new EnhavoAdapter();
    exports.default = adapter;
});
