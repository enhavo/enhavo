define(["require", "exports", "app/Admin"], function (require, exports, Admin_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var UploadForm = /** @class */ (function () {
        function UploadForm() {
        }
        UploadForm.init = function () {
            Admin_1.Admin.console();
            Admin_1.Admin.hello();
            console.log('test');
        };
        return UploadForm;
    }());
});
