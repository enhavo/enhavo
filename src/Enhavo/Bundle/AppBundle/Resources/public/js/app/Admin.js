define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Admin = /** @class */ (function () {
        function Admin() {
        }
        Admin.console = function () {
            console.log('test');
        };
        Admin.hello = function () {
            console.log('gggg hello');
        };
        return Admin;
    }());
    exports.Admin = Admin;
});
