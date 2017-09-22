define(["require", "exports", "media/Media", "app/Form"], function (require, exports, Media_1, form) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var EnhavoAdapter = /** @class */ (function () {
        function EnhavoAdapter() {
            EnhavoAdapter.initFormListener();
        }
        EnhavoAdapter.initFormListener = function () {
            $(document).on('formOpenAfter', function (event, form) {
                Media_1.Media.apply(form);
            });
            $(document).on('formListAddItem', function (event, form) {
                Media_1.Media.apply(form);
            });
            $(document).on('mediaAddItem', function (event, item) {
                var placeholder = $(item.getRow()).find('[data-form-placeholder]').data('form-placeholder');
                form.initReindexableItem(item.getElement(), placeholder);
                form.reindex();
            });
        };
        EnhavoAdapter.prototype.initMedia = function (form) {
            Media_1.Media.apply(form);
        };
        return EnhavoAdapter;
    }());
    exports.enhavoAdapter = new EnhavoAdapter();
});
