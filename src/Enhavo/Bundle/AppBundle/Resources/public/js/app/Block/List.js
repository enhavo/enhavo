define(["require", "exports", "jquery", "app/Router"], function (require, exports, $, router) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var ListBlock = /** @class */ (function () {
        function ListBlock(element) {
            this.$element = $(element);
            this.route = this.$element.data('block-list-route');
            this.$container = this.$element.find('[data-list]');
            this.load();
        }
        ListBlock.prototype.load = function () {
            this.$element.addClass('loading');
            this.getList(null);
        };
        ListBlock.prototype.getList = function (parent) {
            var self = this;
            var url = router.generate(this.route, {
                parent: parent
            });
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    self.$element.removeClass('loading');
                    self.addToList(data);
                }
            });
        };
        ListBlock.prototype.addToList = function (html) {
            this.$container.html(html);
        };
        return ListBlock;
    }());
    exports.ListBlock = ListBlock;
});
