var __extends = (this && this.__extends) || (function () {
    var extendStatics = Object.setPrototypeOf ||
        ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
        function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var FormInitializer = /** @class */ (function () {
        function FormInitializer() {
            this.converted = false;
            this.released = false;
            this.inserted = false;
        }
        FormInitializer.prototype.setHtml = function (html) {
            this.html = html.trim();
        };
        FormInitializer.prototype.setElement = function (element) {
            this.element = element;
        };
        FormInitializer.prototype.getElement = function () {
            return this.element;
        };
        FormInitializer.prototype.insertBefore = function (element) {
            this.insert();
            $(this.element).insertBefore(element);
            this.release();
        };
        FormInitializer.prototype.insertAfter = function (element) {
            this.insert();
            $(this.element).insertAfter(element);
            this.release();
        };
        FormInitializer.prototype.convert = function () {
            if (!this.converted) {
                this.converted = true;
                if (this.html) {
                    var event_1 = new FormConvertEvent(this.html);
                    $('body').trigger('formConvert', event_1);
                    this.html = event_1.getHtml();
                    this.element = $($.parseHTML(this.html)).get(0);
                }
            }
        };
        FormInitializer.prototype.release = function () {
            if (!this.inserted) {
                this.insert();
            }
            if (!this.released) {
                this.released = true;
                var event_2 = new FormReleaseEvent(this.element);
                $('body').trigger('formRelease', event_2);
                this.element = event_2.getElement();
            }
        };
        FormInitializer.prototype.insert = function () {
            if (!this.inserted) {
                this.inserted = true;
                if (!this.converted) {
                    this.convert();
                }
                var event_3 = new FormInsertEvent(this.element);
                $('body').trigger('formInsert', event_3);
                $(document).trigger('gridAddAfter', [this.element]);
                this.element = event_3.getElement();
            }
        };
        return FormInitializer;
    }());
    exports.FormInitializer = FormInitializer;
    var FormListener = /** @class */ (function () {
        function FormListener() {
        }
        FormListener.prototype.onConvert = function (callback) {
            $('body').on('formConvert', function (event, formEvent) {
                callback(formEvent);
            });
        };
        FormListener.prototype.onInsert = function (callback) {
            $('body').on('formInsert', function (event, formEvent) {
                callback(formEvent);
            });
        };
        FormListener.prototype.onRelease = function (callback) {
            $('body').on('formRelease', function (event, formEvent) {
                callback(formEvent);
            });
        };
        return FormListener;
    }());
    exports.FormListener = FormListener;
    var FormConvertEvent = /** @class */ (function () {
        function FormConvertEvent(html) {
            this.html = html;
        }
        FormConvertEvent.prototype.setHtml = function (html) {
            this.html = html;
        };
        FormConvertEvent.prototype.getHtml = function () {
            return this.html;
        };
        return FormConvertEvent;
    }());
    exports.FormConvertEvent = FormConvertEvent;
    var FormElementEvent = /** @class */ (function () {
        function FormElementEvent(element) {
            this.element = element;
        }
        FormElementEvent.prototype.setElement = function (element) {
            this.element = element;
        };
        FormElementEvent.prototype.getElement = function () {
            return this.element;
        };
        return FormElementEvent;
    }());
    exports.FormElementEvent = FormElementEvent;
    var FormReleaseEvent = /** @class */ (function (_super) {
        __extends(FormReleaseEvent, _super);
        function FormReleaseEvent() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        return FormReleaseEvent;
    }(FormElementEvent));
    exports.FormReleaseEvent = FormReleaseEvent;
    var FormInsertEvent = /** @class */ (function (_super) {
        __extends(FormInsertEvent, _super);
        function FormInsertEvent() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        return FormInsertEvent;
    }(FormElementEvent));
    exports.FormInsertEvent = FormInsertEvent;
});
