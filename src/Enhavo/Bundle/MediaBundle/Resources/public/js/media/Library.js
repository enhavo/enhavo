define(["require", "exports", "jquery"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Library = /** @class */ (function () {
        function Library(element) {
            this.$element = $(element);
        }
        return Library;
    }());
    exports.Library = Library;
    exports.library = new Library();
});
