define(["require", "exports", "jquery"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Menu = /** @class */ (function () {
        function Menu(element) {
            this.list = [];
            this.$element = $(element);
            this.initList();
        }
        Menu.prototype.initList = function () {
            var self = this;
            this.$element.find('[data-menu-list]').each(function (index, element) {
                self.list.push(new List(element));
            });
        };
        Menu.init = function () {
            var element = $('[data-menu]').get(0);
            var menu = new Menu(element);
        };
        return Menu;
    }());
    exports.Menu = Menu;
    var List = /** @class */ (function () {
        function List(element) {
            this.$element = $(element);
            this.openFlag = this.$element.hasClass('open');
            this.init();
        }
        List.prototype.init = function () {
            var self = this;
            this.$element.find('[data-menu-list-button]').click(function () {
                if (self.isOpen()) {
                    self.close();
                }
                else {
                    self.open();
                }
            });
        };
        List.prototype.isOpen = function () {
            return this.openFlag;
        };
        List.prototype.open = function () {
            this.openFlag = true;
            this.$element.addClass('open');
            this.$element.find('[data-menu-list-container]').show();
        };
        List.prototype.close = function () {
            this.openFlag = false;
            this.$element.removeClass('open');
            this.$element.find('[data-menu-list-container]').hide();
        };
        return List;
    }());
    exports.List = List;
    Menu.init();
});
