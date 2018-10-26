define(["require", "exports", "jquery"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Application = /** @class */ (function () {
        function Application(element) {
            this.$application = $(element);
            this.$overlayStack = this.$application.find('[data-view-overlay-stack]');
        }
        Application.prototype.pushOverlayView = function (option) {
            this.$overlayStack.height('100%');
            if (option == null) {
                option = new OverlayOption;
            }
            var overlay = new Overlay(this, option);
            this.$overlayStack.append(overlay.getHtml());
            return overlay;
        };
        Application.prototype.handleRemoveOverlay = function () {
            if (this.$overlayStack.children().length == 0) {
                this.$overlayStack.height('0');
            }
        };
        return Application;
    }());
    var OverlayOption = /** @class */ (function () {
        function OverlayOption() {
            this.closeSelector = '[data-overlay-close]';
        }
        return OverlayOption;
    }());
    exports.OverlayOption = OverlayOption;
    var Overlay = /** @class */ (function () {
        function Overlay(application, option) {
            var html = $.parseHTML('<div class="overlay-view"><div class="overlay-loading" data-overlay-loading><i class="loading-icon icon-spinner icon-spin"></i></div><div class="overlay-background"></div><div class="overlay-content" data-overlay-content></div></div>')[0];
            this.$element = $(html);
            this.$content = this.$element.find('[data-overlay-content]');
            this.$loading = this.$element.find('[data-overlay-loading]');
            this.option = option;
            this.application = application;
        }
        Overlay.prototype.init = function () {
            var self = this;
            this.$content.find(this.option.closeSelector).on('click', function () {
                self.close();
            });
            if (typeof this.option.init == 'function') {
                this.option.init(this);
            }
        };
        Overlay.prototype.setContent = function (html) {
            this.$content.html(html);
            this.init();
            return this;
        };
        Overlay.prototype.getHtml = function () {
            return this.$element.get(0);
        };
        Overlay.prototype.wait = function (wait) {
            this.$loading.show();
            var self = this;
            wait(function () {
                self.$loading.hide();
            }, this);
            return this;
        };
        Overlay.prototype.closeSelector = function (selector) {
            this.option.closeSelector = selector;
            return this;
        };
        Overlay.prototype.onClose = function (callback) {
            this.option.close = callback;
            return this;
        };
        Overlay.prototype.onInit = function (callback) {
            this.option.init = callback;
            return this;
        };
        Overlay.prototype.close = function () {
            if (typeof this.option.close == 'function') {
                this.option.close(this);
            }
            this.$element.remove();
            this.application.handleRemoveOverlay();
        };
        return Overlay;
    }());
    exports.Overlay = Overlay;
    var application = new Application(document.body);
    exports.default = application;
});
