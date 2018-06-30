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
define(["require", "exports", "jquery"], function (require, exports, $) {
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
        FormInitializer.prototype.append = function (element) {
            this.insert();
            $(element).append(this.element);
        };
        FormInitializer.prototype.convert = function () {
            if (!this.converted) {
                this.converted = true;
                if (this.html) {
                    this.html = FormDispatcher.dispatchConvert(this.html).getHtml();
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
                this.element = FormDispatcher.dispatchRelease(this.element).getElement();
            }
        };
        FormInitializer.prototype.init = function () {
            this.release();
        };
        FormInitializer.prototype.insert = function () {
            if (!this.inserted) {
                this.inserted = true;
                if (!this.converted) {
                    this.convert();
                }
                this.element = FormDispatcher.dispatchInsert(this.element).getElement();
            }
        };
        return FormInitializer;
    }());
    exports.FormInitializer = FormInitializer;
    var FormDispatcher = /** @class */ (function () {
        function FormDispatcher() {
        }
        FormDispatcher.dispatchMove = function (element) {
            var event = new FormElementEvent(element);
            $('body').trigger('formInsert', event);
            return event;
        };
        FormDispatcher.dispatchDrop = function (element) {
            var event = new FormElementEvent(element);
            $('body').trigger('formInsert', event);
            return event;
        };
        FormDispatcher.dispatchConvert = function (element) {
            var event = new FormConvertEvent(element);
            $('body').trigger('formConvert', event);
            return event;
        };
        FormDispatcher.dispatchInsert = function (element) {
            var event = new FormInsertEvent(element);
            $('body').trigger('formInsert', event);
            return event;
        };
        FormDispatcher.dispatchRelease = function (element) {
            var event = new FormReleaseEvent(element);
            $('body').trigger('formRelease', event);
            return event;
        };
        return FormDispatcher;
    }());
    exports.FormDispatcher = FormDispatcher;
    var FormListener = /** @class */ (function () {
        function FormListener() {
        }
        FormListener.onConvert = function (callback) {
            $('body').on('formConvert', function (event, formEvent) {
                callback(formEvent);
            });
        };
        FormListener.onInsert = function (callback) {
            $('body').on('formInsert', function (event, formEvent) {
                callback(formEvent);
            });
        };
        FormListener.onRelease = function (callback) {
            $('body').on('formRelease', function (event, formEvent) {
                callback(formEvent);
            });
        };
        FormListener.onMove = function (callback) {
            $('body').on('formMove', function (event, formEvent) {
                callback(formEvent);
            });
        };
        FormListener.onDrop = function (callback) {
            $('body').on('formDrop', function (event, formEvent) {
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
    var Form = /** @class */ (function () {
        function Form(element) {
            this.$element = $(element);
            this.init();
        }
        Form.prototype.init = function () {
        };
        return Form;
    }());
    var FormElement = /** @class */ (function () {
        function FormElement(element) {
            this.$element = $(element);
            this.init();
        }
        FormElement.findElements = function (element, selector) {
            var data = [];
            if ($(element).is(selector)) {
                data.push(element);
            }
            $(element).find(selector).each(function (index, element) {
                data.push(element);
            });
            return data;
        };
        return FormElement;
    }());
    exports.FormElement = FormElement;
});
