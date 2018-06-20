define(["require", "exports", "jquery", "app/Router", "app/Admin"], function (require, exports, $, router, admin) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var ListBlock = /** @class */ (function () {
        function ListBlock(element) {
            this.items = [];
            this.$element = $(element);
            this.route = this.$element.data('block-list-route');
            this.updateRoute = this.$element.data('block-update-route');
            this.updateRouteParameters = this.$element.data('block-update-route-parameters');
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
            this.items = [];
            var self = this;
            this.$container.html(html);
            this.$container.find('[data-list-item]').each(function (index, element) {
                self.items.push(new ListItem(element, self.updateRoute, self.updateRouteParameters));
            });
        };
        return ListBlock;
    }());
    exports.ListBlock = ListBlock;
    var ListItem = /** @class */ (function () {
        function ListItem(element, route, routeParameters) {
            this.$element = $(element);
            this.$children = this.$element.find('[data-list-children]');
            this.id = this.$element.children('[data-item]').data('item');
            this.route = route;
            this.routeParameters = routeParameters ? routeParameters : new RouteParameters;
            this.routeParameters.id = this.id;
            this.initListener();
        }
        ListItem.prototype.initListener = function () {
            var self = this;
            this.$element.children('[data-item]').click(function (event) {
                var $target = $(event.target);
                // check if link
                var isLink = false;
                $target.parentsUntil('[data-item-id]').each(function () {
                    if ($(this).is('a')) {
                        isLink = true;
                    }
                });
                if ($target.is('a')) {
                    isLink = true;
                }
                if (isLink) {
                    return true;
                }
                self.open();
            });
            this.$element.children('[data-item]').find('[data-collapse] a').click(function (event) {
                event.preventDefault();
                self.collapse();
            });
            this.$element.children('[data-item]').find('[data-expand] a').click(function (event) {
                event.preventDefault();
                self.expand();
            });
        };
        ListItem.prototype.open = function () {
            if (this.route) {
                var url = router.generate(this.route, this.routeParameters);
                admin.ajaxOverlay(url);
            }
        };
        ListItem.prototype.collapse = function () {
            console.log('collapse');
            this.$element.removeClass('expand');
        };
        ListItem.prototype.expand = function () {
            this.$element.addClass('expand');
        };
        return ListItem;
    }());
    var RouteParameters = /** @class */ (function () {
        function RouteParameters() {
        }
        return RouteParameters;
    }());
});
