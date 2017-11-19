define(["require", "exports", "app/Admin", "app/Router", "app/Form"], function (require, exports, admin, router, form) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Grid = /** @class */ (function () {
        function Grid(element) {
            this.items = [];
            this.placeholderIndex = 0;
            this.collapse = true;
            this.$element = $(element);
            this.$container = this.$element.find('[data-grid-container]');
            this.initMenu();
            this.initActions();
            this.initItems();
            this.initContainer();
        }
        Grid.apply = function (element) {
            $(element).find('[data-grid]').each(function () {
                new Grid(this);
            });
        };
        Grid.prototype.initItems = function () {
            var items = [];
            var grid = this;
            this.$element.find('[data-grid-item]').each(function () {
                items.push(new GridItem(this, grid));
            });
            this.items = items;
        };
        Grid.prototype.initContainer = function () {
            var grid = this;
            if (typeof this.$container.attr('data-reindexable') != 'undefined') {
                // Save initial index
                this.$container.data('initial-list-index', this.$container.children('[data-grid-item]').length);
            }
            this.setOrder();
            form.reindex();
            this.$container.children('[data-grid-add-button]').each(function () {
                new GridItemAddButton(this, grid);
            });
        };
        Grid.prototype.initActions = function () {
            var grid = this;
            if (this.collapse) {
                grid.collapseAll();
            }
            else {
                grid.expandAll();
            }
            this.$element.find('[data-grid-action-collapse-all]').click(function () {
                grid.collapseAll();
            });
            this.$element.find('[data-grid-action-expand-all]').click(function () {
                grid.expandAll();
            });
        };
        Grid.prototype.collapseAll = function () {
            this.$element.find('[data-grid-action-collapse-all]').hide();
            this.$element.find('[data-grid-action-expand-all]').show();
            this.collapse = true;
            for (var _i = 0, _a = this.items; _i < _a.length; _i++) {
                var item = _a[_i];
                item.collapse();
            }
        };
        Grid.prototype.expandAll = function () {
            this.$element.find('[data-grid-action-collapse-all]').show();
            this.$element.find('[data-grid-action-expand-all]').hide();
            this.collapse = false;
            for (var _i = 0, _a = this.items; _i < _a.length; _i++) {
                var item = _a[_i];
                item.expand();
            }
        };
        Grid.prototype.initMenu = function () {
            var element = this.$element.find('[data-grid-menu]').get(0);
            this.menu = new GridMenu(element, this);
        };
        Grid.prototype.getMenu = function () {
            return this.menu;
        };
        Grid.prototype.isCollapse = function () {
            return this.collapse;
        };
        Grid.prototype.addItem = function (type, button) {
            var url = router.generate('enhavo_grid_item', {
                type: type
            });
            // Generate unique placeholder for reindexing service
            var placeholder = '__grid_name' + this.placeholderIndex + '__';
            var formName = this.$element.data('grid-name') + '[items][' + placeholder + ']';
            this.placeholderIndex++;
            var grid = this;
            this.startLoading();
            $.ajax({
                type: 'POST',
                data: {
                    formName: formName
                },
                url: url,
                success: function (data) {
                    grid.endLoading();
                    data = $.parseHTML(data.trim());
                    grid.items.push(new GridItem(data, grid));
                    // Initialize sub-elements for reindexing
                    form.initReindexableItem(data, placeholder);
                    $(document).trigger('gridAddAfter', [data]);
                    $(button.getElement()).after(data);
                    var newButton = grid.createAddButton();
                    $(data).after(newButton.getElement());
                    grid.setOrder();
                    form.reindex();
                },
                error: function () {
                    grid.endLoading();
                }
            });
        };
        Grid.prototype.createAddButton = function () {
            var html = this.$element.data('grid-add-button-template').trim();
            var element = $.parseHTML(html)[0];
            return new GridItemAddButton(element, this);
        };
        Grid.prototype.setOrder = function () {
            this.$container.children().children('[data-grid-item-order]').each(function (index, element) {
                $(element).val(index + 1);
            });
        };
        ;
        Grid.prototype.startLoading = function () {
            admin.openLoadingOverlay();
        };
        Grid.prototype.endLoading = function () {
            admin.closeLoadingOverlay();
        };
        Grid.prototype.moveItemUp = function (item) {
            var index = this.$container.children('[data-grid-item]').index(item.getElement());
            if (index > 0) {
                var buttonToMove = this.$container.children('[data-grid-add-button]').get(index + 1);
                var buttonTarget = this.$container.children('[data-grid-add-button]').get(index - 1);
                $(buttonTarget).after(item.getElement());
                $(item.getElement()).after(buttonToMove);
            }
            this.setOrder();
            form.reindex();
        };
        Grid.prototype.moveItemDown = function (item) {
            var index = this.$container.children('[data-grid-item]').index(item.getElement());
            var size = this.$container.children('[data-grid-item]').length;
            if (index < (size - 1)) {
                var buttonToMove = this.$container.children('[data-grid-add-button]').get(index + 1);
                var buttonTarget = this.$container.children('[data-grid-add-button]').get(index + 2);
                $(buttonTarget).after(item.getElement());
                $(item.getElement()).after(buttonToMove);
            }
            this.setOrder();
            form.reindex();
        };
        Grid.prototype.removeItem = function (item) {
            $(item.getElement()).next().remove();
            $(item.getElement()).remove();
            var index = this.items.indexOf(item);
            if (index > -1) {
                this.items.splice(index, 1);
            }
        };
        return Grid;
    }());
    exports.Grid = Grid;
    var GridMenu = /** @class */ (function () {
        function GridMenu(element, grid) {
            this.grid = grid;
            this.$element = $(element);
            this.initActions();
        }
        GridMenu.prototype.initActions = function () {
            var menu = this;
            this.$element.find('[data-grid-menu-item]').click(function () {
                var name = $(this).data('grid-menu-item');
                menu.grid.addItem(name, menu.button);
                menu.hide();
            });
        };
        GridMenu.prototype.show = function (button) {
            if (this.button === button) {
                this.hide();
                return;
            }
            this.button = button;
            var position = $(button.getElement()).position();
            var dropDown = true;
            var topOffset = $(button.getElement()).offset().top;
            var windowHeight = $(window).height();
            var menuHeight = this.$element.height();
            var buttonHeight = $(button.getElement()).height();
            if ((topOffset + menuHeight + buttonHeight) > windowHeight) {
                dropDown = false;
            }
            if (dropDown) {
                this.$element.addClass('topTriangle');
                this.$element.css('top', 35 + position.top + 'px');
            }
            else {
                this.$element.addClass('bottomTriangle');
                this.$element.css('top', -1 * this.$element.height() - 25 + position.top + 'px');
            }
            this.$element.css('left', position.left + 'px');
            this.$element.show();
        };
        GridMenu.prototype.hide = function () {
            this.button = null;
            this.$element.hide();
        };
        return GridMenu;
    }());
    exports.GridMenu = GridMenu;
    var GridItem = /** @class */ (function () {
        function GridItem(element, grid) {
            this.grid = grid;
            this.$element = $(element);
            this.initActions();
            if (grid.isCollapse()) {
                this.collapse();
            }
            else {
                this.expand();
            }
        }
        GridItem.prototype.initActions = function () {
            var grid = this;
            this.$element.find('[data-grid-item-action-up]').click(function () {
                grid.up();
            });
            this.$element.find('[data-grid-item-action-down]').click(function () {
                grid.down();
            });
            this.$element.find('[data-grid-item-action-remove]').click(function () {
                grid.remove();
            });
            this.$element.find('[data-grid-item-action-collapse]').click(function () {
                grid.collapse();
            });
            this.$element.find('[data-grid-item-action-expand]').click(function () {
                grid.expand();
            });
        };
        GridItem.prototype.getElement = function () {
            return this.$element.get(0);
        };
        GridItem.prototype.collapse = function () {
            this.$element.find('[data-grid-item-action-expand]').show();
            this.$element.find('[data-grid-item-action-collapse]').hide();
            this.$element.find('[data-grid-item-container]').hide();
        };
        GridItem.prototype.expand = function () {
            this.$element.find('[data-grid-item-action-expand]').hide();
            this.$element.find('[data-grid-item-action-collapse]').show();
            this.$element.find('[data-grid-item-container]').show();
        };
        GridItem.prototype.up = function () {
            var wyswigs = this.$element.find('[data-wysiwyg]');
            if (wyswigs.length) {
                form.destroyWysiwyg(this.$element);
                this.grid.moveItemUp(this);
                form.initWysiwyg(this.getElement());
            }
            else {
                this.grid.moveItemUp(this);
            }
        };
        GridItem.prototype.down = function () {
            var wyswigs = this.$element.find('[data-wysiwyg]');
            if (wyswigs.length) {
                form.destroyWysiwyg(this.$element);
                this.grid.moveItemDown(this);
                form.initWysiwyg(this.getElement());
            }
            else {
                this.grid.moveItemDown(this);
            }
        };
        GridItem.prototype.remove = function () {
            this.grid.removeItem(this);
        };
        return GridItem;
    }());
    exports.GridItem = GridItem;
    var GridItemAddButton = /** @class */ (function () {
        function GridItemAddButton(element, grid) {
            this.grid = grid;
            this.$element = $(element);
            var that = this;
            this.$element.click(function () {
                grid.getMenu().show(that);
            });
        }
        GridItemAddButton.prototype.getElement = function () {
            return this.$element.get(0);
        };
        return GridItemAddButton;
    }());
    exports.GridItemAddButton = GridItemAddButton;
});
