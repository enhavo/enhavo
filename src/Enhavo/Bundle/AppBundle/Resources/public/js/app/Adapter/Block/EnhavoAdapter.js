define(["require", "exports", "app/app/Block/List"], function (require, exports, List_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var EnhavoAdapter = /** @class */ (function () {
        function EnhavoAdapter() {
            EnhavoAdapter.initFormListener();
        }
        EnhavoAdapter.initFormListener = function () {
            var lists = [];
            $('body').on('initBlock', function (event, data) {
                if (data.type == 'list') {
                    lists.push(new List_1.ListBlock(data.block));
                }
            });
            $(document).on('formSaveAfter', function () {
                for (var _i = 0, lists_1 = lists; _i < lists_1.length; _i++) {
                    var list = lists_1[_i];
                    list.load();
                }
                //admin.closeLoadingOverlay();
            });
        };
        return EnhavoAdapter;
    }());
    var adapter = new EnhavoAdapter();
    exports.default = adapter;
});
