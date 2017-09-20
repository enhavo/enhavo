define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var EnhavoAdapter = /** @class */ (function () {
        function EnhavoAdapter() {
        }
        EnhavoAdapter.initFormListener = function () {
            $(document).on('formOpenAfter', function (event, form) {
                //Media.initUploads(form);
            });
            $(document).on('formListAddItem', function (event, form) {
                //Media.initUploads(form);
            });
        };
        EnhavoAdapter.initUploads = function (form) {
            $(form).find('.uploadForm').each(function (formIndex, uploadForm) {
                //Media.initUpload(uploadForm);
            });
        };
        return EnhavoAdapter;
    }());
    exports.default = new EnhavoAdapter();
});
