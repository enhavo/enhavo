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
define(["require", "exports", "app/Form/Form", "app/Form/Form", "jquery", "tinymce", "jquery-ui-timepicker", "jquery-tinymce", "icheck", "select2"], function (require, exports, Form_1, Form_2, $, tinymce) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var DatePickerType = /** @class */ (function (_super) {
        __extends(DatePickerType, _super);
        function DatePickerType() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        DatePickerType.apply = function (element) {
            var data = [];
            var elements = Form_1.FormElement.findElements(element, '[data-date-picker]');
            for (var _i = 0, elements_1 = elements; _i < elements_1.length; _i++) {
                element = elements_1[_i];
                data.push(new DatePickerType(element));
            }
            return data;
        };
        DatePickerType.prototype.init = function () {
            this.$element.datepicker({
                closeText: 'Fertig',
                dateFormat: 'dd.mm.yy',
                firstDay: 1
            });
        };
        return DatePickerType;
    }(Form_1.FormElement));
    exports.DatePickerType = DatePickerType;
    var DateTimePickerType = /** @class */ (function (_super) {
        __extends(DateTimePickerType, _super);
        function DateTimePickerType() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        DateTimePickerType.apply = function (element) {
            var data = [];
            var elements = Form_1.FormElement.findElements(element, '[data-date-time-picker]');
            for (var _i = 0, elements_2 = elements; _i < elements_2.length; _i++) {
                element = elements_2[_i];
                data.push(new DateTimePickerType(element));
            }
            return data;
        };
        DateTimePickerType.prototype.init = function () {
            this.$element.datetimepicker({
                timeFormat: 'hh:mm',
                timeText: 'Zeit',
                hourText: 'Std.',
                minuteText: 'Min.',
                currentText: 'Jetzt',
                closeText: 'Fertig',
                dateFormat: 'dd.mm.yy',
                stepMinute: 5,
                firstDay: 1
            });
        };
        return DateTimePickerType;
    }(Form_1.FormElement));
    exports.DateTimePickerType = DateTimePickerType;
    var CheckboxType = /** @class */ (function (_super) {
        __extends(CheckboxType, _super);
        function CheckboxType() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        CheckboxType.apply = function (element) {
            var data = [];
            var elements = Form_1.FormElement.findElements(element, 'input[type=radio],input[type=checkbox]');
            for (var _i = 0, elements_3 = elements; _i < elements_3.length; _i++) {
                element = elements_3[_i];
                data.push(new CheckboxType(element));
            }
            return data;
        };
        CheckboxType.prototype.init = function () {
            this.$element.iCheck({
                checkboxClass: 'icheckbox',
                radioClass: 'icheckbox'
            });
        };
        return CheckboxType;
    }(Form_1.FormElement));
    exports.CheckboxType = CheckboxType;
    var SelectType = /** @class */ (function (_super) {
        __extends(SelectType, _super);
        function SelectType() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        SelectType.apply = function (element) {
            var data = [];
            var elements = Form_1.FormElement.findElements(element, 'select');
            for (var _i = 0, elements_4 = elements; _i < elements_4.length; _i++) {
                element = elements_4[_i];
                data.push(new SelectType(element));
            }
            return data;
        };
        SelectType.prototype.init = function () {
            this.$element.select2();
        };
        return SelectType;
    }(Form_1.FormElement));
    exports.SelectType = SelectType;
    var WysiwygType = /** @class */ (function (_super) {
        __extends(WysiwygType, _super);
        function WysiwygType() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        WysiwygType.apply = function (element) {
            var data = [];
            var elements = Form_1.FormElement.findElements(element, '[data-wysiwyg]');
            for (var _i = 0, elements_5 = elements; _i < elements_5.length; _i++) {
                element = elements_5[_i];
                data.push(new WysiwygType(element));
            }
            return data;
        };
        WysiwygType.prototype.generateId = function () {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        };
        WysiwygType.prototype.init = function () {
            /**
             * The id of the input field in tiny mce MUST be unique, otherwise it can't be initialized.
             * To make sure it has a unique id, we generate our own at on first initialize
             */
            var id = this.$element.attr('id');
            if (typeof id === 'undefined' || id === null || !id.match(/^wysiwyg_/)) {
                id = 'wysiwyg_' + this.generateId();
                this.$element.attr('id', id);
            }
            var options = {
                menubar: false,
                // General options
                force_br_newlines: false,
                force_p_newlines: true,
                forced_root_block: "p",
                cleanup: false,
                cleanup_on_startup: false,
                font_size_style_values: "xx-small,x-small,small,medium,large,x-large,xx-large",
                convert_fonts_to_spans: true,
                resize: false,
                relative_urls: false,
                oninit: function (ed) {
                    $(ed.contentAreaContainer).droppable({
                        accept: ".imgList li.imgContainer",
                        drop: function (event, ui) {
                            var draggedImg = ui.draggable.find("img");
                            var src = '';
                            if (typeof draggedImg.attr("largesrc") == "undefined") {
                                src = draggedImg.attr("src");
                            }
                            else {
                                src = draggedImg.attr("largesrc");
                            }
                            ed.execCommand('mceInsertContent', false, "<img src=\"" + src + "\" />");
                        }
                    });
                }
            };
            var config = this.$element.data('config');
            if (config.height) {
                options.height = config.height;
            }
            if (config.plugins) {
                options.plugins = config.plugins;
            }
            else {
                options.plugins = ["advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste autoresize"];
            }
            if (config.style_formats) {
                options.style_formats = config.style_formats;
            }
            if (config.formats) {
                options.formats = config.formats;
            }
            if (config.toolbar1) {
                options.toolbar1 = config.toolbar1;
            }
            if (config.toolbar2) {
                options.toolbar2 = config.toolbar2;
            }
            if (config.content_css) {
                options.content_css = config.content_css;
            }
            this.$element.tinymce(options);
        };
        WysiwygType.prototype.destroy = function () {
            tinymce.remove(this);
        };
        return WysiwygType;
    }(Form_1.FormElement));
    exports.WysiwygType = WysiwygType;
    var WysiwygConfig = /** @class */ (function () {
        function WysiwygConfig() {
        }
        return WysiwygConfig;
    }());
    var ListType = /** @class */ (function (_super) {
        __extends(ListType, _super);
        function ListType() {
            var _this = _super !== null && _super.apply(this, arguments) || this;
            _this.placeholderIndex = 0;
            return _this;
        }
        ListType.apply = function (element) {
            var data = [];
            var elements = Form_1.FormElement.findElements(element, '[data-list]');
            for (var _i = 0, elements_6 = elements; _i < elements_6.length; _i++) {
                element = elements_6[_i];
                data.push(new ListType(element));
            }
            return data;
        };
        ListType.prototype.init = function () {
            this.initItems();
            this.initAddButton();
        };
        ListType.prototype.initItems = function () {
            var self = this;
            this.items = [];
            this.$element.children('[data-list-container]').children().each(function (index, element) {
                self.items.push(new ListItem(element, self));
            });
        };
        ListType.prototype.initAddButton = function () {
            var self = this;
            this.$element.children('[data-add-button]').click(function (event) {
                event.preventDefault();
                var $listContainer = self.$element.children('[data-list-container]');
                // grab the prototype template
                var item = $listContainer.attr('data-prototype');
                var prototype_name = $listContainer.attr('data-prototype-name');
                // generate unique placeholder for reindexing service
                var placeholder = '__name' + self.placeholderIndex + '__';
                self.placeholderIndex++;
                item = item.replace(new RegExp(prototype_name, 'g'), placeholder).trim();
                var initializer = new Form_1.FormInitializer;
                initializer.setHtml(item);
                initializer.append($listContainer.get(0));
                self.items.push(new ListItem(initializer.getElement(), self));
                self.updatePosition();
            });
        };
        ListType.prototype.removeItem = function (item) {
            var index = this.items.indexOf(item);
            this.items.splice(index, index);
        };
        ListType.prototype.moveItemUp = function (item) {
            var index = this.$element.children('[data-list-container]').children().index(item.getElement());
            console.log(index);
            if (index > 0) {
                Form_2.FormDispatcher.dispatchMove(item.getElement());
                var before = this.$element.children('[data-list-container]').children().get(index - 1);
                before.before(item.getElement());
                Form_2.FormDispatcher.dispatchDrop(item.getElement());
            }
            this.updatePosition();
        };
        ListType.prototype.moveItemDown = function (item) {
            var index = this.$element.children('[data-list-container]').children().index(item.getElement());
            var size = this.$element.children('[data-list-container]').children().length;
            if (index < (size - 1)) {
                Form_2.FormDispatcher.dispatchMove(item.getElement());
                var after = this.$element.children('[data-list-container]').children().get(index + 1);
                after.after(item.getElement());
                Form_2.FormDispatcher.dispatchDrop(item.getElement());
            }
            this.updatePosition();
        };
        ListType.prototype.updatePosition = function () {
            var i = 0;
            for (var _i = 0, _a = this.items; _i < _a.length; _i++) {
                var item = _a[_i];
                i++;
                item.setPosition(i);
            }
        };
        return ListType;
    }(Form_1.FormElement));
    exports.ListType = ListType;
    var ListItem = /** @class */ (function () {
        function ListItem(element, list) {
            this.$element = $(element);
            this.$buttons = this.$element.children('[data-list-item-buttons]');
            this.list = list;
            this.initDeleteButton();
            this.initUpButton();
            this.initDownButton();
        }
        ListItem.prototype.initDeleteButton = function () {
            var self = this;
            this.$buttons.children('[data-list-item-delete]').click(function (event) {
                self.$element.remove();
            });
        };
        ListItem.prototype.initUpButton = function () {
            var self = this;
            this.$buttons.children('[data-list-item-up]').click(function (event) {
                event.preventDefault();
                event.stopPropagation();
                self.list.moveItemUp(self);
            });
        };
        ListItem.prototype.initDownButton = function () {
            var self = this;
            this.$buttons.children('[data-list-item-down]').click(function (event) {
                event.preventDefault();
                event.stopPropagation();
                self.list.moveItemDown(self);
            });
        };
        ListItem.prototype.getElement = function () {
            return this.$element.get(0);
        };
        ListItem.prototype.setPosition = function (number) {
            this.$element.find('[data-position]').val(number);
        };
        return ListItem;
    }());
});
