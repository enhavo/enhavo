define(["require", "exports", "app/Admin", "app/Router", "app/Form"], function (require, exports, admin, router, form) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var DynamicFormConfig = /** @class */ (function () {
        function DynamicFormConfig() {
        }
        return DynamicFormConfig;
    }());
    exports.DynamicFormConfig = DynamicFormConfig;
    var DynamicForm = /** @class */ (function () {
        function DynamicForm(element, config, scope) {
            if (config === void 0) { config = null; }
            if (scope === void 0) { scope = null; }
            this.items = [];
            this.placeholderIndex = 0;
            this.collapse = true;
            this.$element = $(element);
            this.$container = this.$element.find('[data-dynamic-form-container]');
            this.scope = scope;
            this.initMenu();
            this.initActions();
            this.initItems();
            this.initContainer();
            if (config == null) {
                this.config = this.$element.data('dynamic-config');
            }
            else {
                this.config = config;
            }
            console.log(this.config);
        }
        DynamicForm.apply = function (element) {
            $(element).find('[data-dynamic-form]').each(function () {
                new DynamicForm(this);
            });
        };
        DynamicForm.prototype.initItems = function () {
            var items = [];
            var dynamicForm = this;
            this.$element.find('[data-dynamic-form-item]').each(function () {
                items.push(new DynamicFormItem(this, dynamicForm));
            });
            this.items = items;
        };
        DynamicForm.prototype.initContainer = function () {
            var dynamicForm = this;
            if (typeof this.$container.attr('data-reindexable') != 'undefined') {
                // Save initial index
                this.$container.data('initial-list-index', this.$container.children('[data-dynamic-form-item]').length);
            }
            this.setOrder();
            form.reindex();
            this.$container.children('[data-dynamic-form-add-button]').each(function () {
                new DynamicFormItemAddButton(this, dynamicForm);
            });
        };
        DynamicForm.prototype.initActions = function () {
            var dynamicForm = this;
            if (this.collapse) {
                dynamicForm.collapseAll();
            }
            else {
                dynamicForm.expandAll();
            }
            this.$element.find('[data-dynamic-form-action-collapse-all]').click(function () {
                dynamicForm.collapseAll();
            });
            this.$element.find('[data-dynamic-form-action-expand-all]').click(function () {
                dynamicForm.expandAll();
            });
        };
        DynamicForm.prototype.getConfig = function () {
            return this.config;
        };
        DynamicForm.prototype.getScope = function () {
            return this.scope;
        };
        DynamicForm.prototype.collapseAll = function () {
            this.$element.find('[data-dynamic-form-action-collapse-all]').hide();
            this.$element.find('[data-dynamic-form-action-expand-all]').show();
            this.collapse = true;
            for (var _i = 0, _a = this.items; _i < _a.length; _i++) {
                var item = _a[_i];
                item.collapse();
            }
        };
        DynamicForm.prototype.expandAll = function () {
            this.$element.find('[data-dynamic-form-action-collapse-all]').show();
            this.$element.find('[data-dynamic-form-action-expand-all]').hide();
            this.collapse = false;
            for (var _i = 0, _a = this.items; _i < _a.length; _i++) {
                var item = _a[_i];
                item.expand();
            }
        };
        DynamicForm.prototype.initMenu = function () {
            var element = this.$element.find('[data-dynamic-form-menu]').get(0);
            this.menu = new DynamicFormMenu(element, this);
        };
        DynamicForm.prototype.getMenu = function () {
            return this.menu;
        };
        DynamicForm.prototype.isCollapse = function () {
            return this.collapse;
        };
        DynamicForm.prototype.addItem = function (type, button) {
            var url = router.generate(this.config.route, {
                type: type
            });
            // Generate unique placeholder for reindexing service
            var placeholder = '__dynamic_form_name' + this.placeholderIndex + '__';
            var formName = this.$element.data('dynamic-form-name') + '[items][' + placeholder + ']';
            this.placeholderIndex++;
            var dynamicForm = this;
            this.startLoading();
            $.ajax({
                type: 'POST',
                data: {
                    formName: formName
                },
                url: url,
                success: function (data) {
                    dynamicForm.endLoading();
                    data = $.parseHTML(data.trim());
                    $(button.getElement()).after(data);
                    dynamicForm.items.push(new DynamicFormItem(data, dynamicForm));
                    // Initialize sub-elements for reindexing
                    form.initReindexableItem(data, placeholder);
                    $(document).trigger('dynamicFormAddAfter', [data]);
                    var newButton = dynamicForm.createAddButton();
                    $(data).after(newButton.getElement());
                    dynamicForm.setOrder();
                    form.reindex();
                },
                error: function () {
                    dynamicForm.endLoading();
                }
            });
        };
        DynamicForm.prototype.createAddButton = function () {
            var html = this.$element.data('dynamic-form-add-button-template').trim();
            var element = $.parseHTML(html)[0];
            return new DynamicFormItemAddButton(element, this);
        };
        DynamicForm.prototype.setOrder = function () {
            this.$container.children().children('[data-dynamic-form-item-order]').each(function (index, element) {
                $(element).val(index + 1);
            });
        };
        ;
        DynamicForm.prototype.startLoading = function () {
            admin.openLoadingOverlay();
        };
        DynamicForm.prototype.endLoading = function () {
            admin.closeLoadingOverlay();
        };
        DynamicForm.prototype.moveItemUp = function (item, callback) {
            if (callback === void 0) { callback = function () { }; }
            var index = this.$container.children('[data-dynamic-form-item]').index(item.getElement());
            var self = this;
            if (index > 0) {
                var buttonToMove_1 = $(this.$container.children('[data-dynamic-form-add-button]').get(index + 1));
                var buttonTarget_1 = $(this.$container.children('[data-dynamic-form-add-button]').get(index - 1));
                var domElementToMove_1 = $(item.getElement());
                domElementToMove_1.slideUp(200, function () {
                    buttonTarget_1.after(domElementToMove_1);
                    domElementToMove_1.after(buttonToMove_1);
                    domElementToMove_1.slideDown(200, function () {
                        self.setOrder();
                        form.reindex();
                        if (typeof callback != "undefined") {
                            callback();
                        }
                    });
                });
            }
            else {
                this.setOrder();
                form.reindex();
                if (typeof callback != "undefined") {
                    callback();
                }
            }
        };
        DynamicForm.prototype.moveItemDown = function (item, callback) {
            if (callback === void 0) { callback = function () { }; }
            var index = this.$container.children('[data-dynamic-form-item]').index(item.getElement());
            var size = this.$container.children('[data-dynamic-form-item]').length;
            var self = this;
            if (index < (size - 1)) {
                var buttonToMove_2 = $(this.$container.children('[data-dynamic-form-add-button]').get(index + 1));
                var buttonTarget_2 = $(this.$container.children('[data-dynamic-form-add-button]').get(index + 2));
                var domElementToMove_2 = $(item.getElement());
                domElementToMove_2.slideUp(200, function () {
                    buttonTarget_2.after(domElementToMove_2);
                    domElementToMove_2.after(buttonToMove_2);
                    domElementToMove_2.slideDown(200, function () {
                        self.setOrder();
                        form.reindex();
                        if (typeof callback != "undefined") {
                            callback();
                        }
                    });
                });
            }
            else {
                this.setOrder();
                form.reindex();
                if (typeof callback != "undefined") {
                    callback();
                }
            }
        };
        DynamicForm.prototype.removeItem = function (item) {
            $(item.getElement()).next().remove();
            $(item.getElement()).css({ opacity: 0, transition: 'opacity 550ms' }).slideUp(350, function () {
                this.remove();
            });
            var index = this.items.indexOf(item);
            if (index > -1) {
                this.items.splice(index, 1);
            }
        };
        return DynamicForm;
    }());
    exports.DynamicForm = DynamicForm;
    var DynamicFormMenu = /** @class */ (function () {
        function DynamicFormMenu(element, dynamicForm) {
            this.dynamicForm = dynamicForm;
            this.$element = $(element);
            this.initActions();
        }
        DynamicFormMenu.prototype.initActions = function () {
            var menu = this;
            this.$element.find('[data-dynamic-form-menu-item]').click(function () {
                var name = $(this).data('dynamic-form-menu-item');
                menu.dynamicForm.addItem(name, menu.button);
                menu.hide();
            });
        };
        DynamicFormMenu.prototype.show = function (button) {
            if (this.button === button) {
                this.hide();
                return;
            }
            this.button = button;
            var position = $(button.getElement()).position();
            var dropDown = true;
            var scope;
            scope = this.dynamicForm.getScope();
            if (scope == null) {
                scope = $('body').get(0);
            }
            var topOffset = this.topToElement(button.getElement(), scope, 0);
            var center = $(button.getElement()).height() / 2 + topOffset;
            var halfHeight = $(scope).height() / 2;
            if (halfHeight < center) {
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
        DynamicFormMenu.prototype.topToElement = function (element, toElement, top) {
            if (top === void 0) { top = 0; }
            var parent = $(element).offsetParent().get(0);
            if (parent == $('html').get(0)) {
                return top;
            }
            var topOffset = $(element).position().top;
            if (toElement == parent) {
                return top + topOffset;
            }
            return this.topToElement(parent, toElement, top + topOffset);
        };
        DynamicFormMenu.prototype.hide = function () {
            this.button = null;
            this.$element.hide();
        };
        return DynamicFormMenu;
    }());
    exports.DynamicFormMenu = DynamicFormMenu;
    var DynamicFormItem = /** @class */ (function () {
        function DynamicFormItem(element, dynamicForm) {
            this.dynamicForm = dynamicForm;
            this.$element = $(element);
            this.initActions();
            if (dynamicForm.isCollapse()) {
                this.collapse();
            }
            else {
                this.expand();
            }
        }
        DynamicFormItem.prototype.initActions = function () {
            var dynamicForm = this;
            this.$element.find('[data-dynamic-form-item-action-up]').click(function () {
                dynamicForm.up();
            });
            this.$element.find('[data-dynamic-form-item-action-down]').click(function () {
                dynamicForm.down();
            });
            this.$element.find('[data-dynamic-form-item-action-remove]').click(function () {
                dynamicForm.remove();
            });
            this.$element.find('[data-dynamic-form-item-action-collapse]').click(function () {
                dynamicForm.collapse();
            });
            this.$element.find('[data-dynamic-form-item-action-expand]').click(function () {
                dynamicForm.expand();
            });
        };
        DynamicFormItem.prototype.getElement = function () {
            return this.$element.get(0);
        };
        DynamicFormItem.prototype.collapse = function () {
            this.$element.find('[data-dynamic-form-item-action-expand]').show();
            this.$element.find('[data-dynamic-form-item-action-collapse]').hide();
            this.$element.find('[data-dynamic-form-item-container]').hide();
        };
        DynamicFormItem.prototype.expand = function () {
            this.$element.find('[data-dynamic-form-item-action-expand]').hide();
            this.$element.find('[data-dynamic-form-item-action-collapse]').show();
            this.$element.find('[data-dynamic-form-item-container]').show();
        };
        DynamicFormItem.prototype.up = function () {
            var wyswigs = this.$element.find('[data-wysiwyg]');
            var self = this;
            if (wyswigs.length) {
                form.destroyWysiwyg(this.$element);
                this.dynamicForm.moveItemUp(this, function () {
                    form.initWysiwyg(self.getElement());
                });
            }
            else {
                this.dynamicForm.moveItemUp(this);
            }
        };
        DynamicFormItem.prototype.down = function () {
            var wyswigs = this.$element.find('[data-wysiwyg]');
            var self = this;
            if (wyswigs.length) {
                form.destroyWysiwyg(this.$element);
                this.dynamicForm.moveItemDown(this, function () {
                    form.initWysiwyg(self.getElement());
                });
            }
            else {
                this.dynamicForm.moveItemDown(this);
            }
        };
        DynamicFormItem.prototype.remove = function () {
            this.dynamicForm.removeItem(this);
        };
        return DynamicFormItem;
    }());
    exports.DynamicFormItem = DynamicFormItem;
    var DynamicFormItemAddButton = /** @class */ (function () {
        function DynamicFormItemAddButton(element, dynamicForm) {
            this.dynamicForm = dynamicForm;
            this.$element = $(element);
            var that = this;
            this.$element.click(function () {
                dynamicForm.getMenu().show(that);
            });
        }
        DynamicFormItemAddButton.prototype.getElement = function () {
            return this.$element.get(0);
        };
        return DynamicFormItemAddButton;
    }());
    exports.DynamicFormItemAddButton = DynamicFormItemAddButton;
});
