import {FormElement, FormInitializer, FormListener} from "app/Form/Form";
import { FormDispatcher } from "app/Form/Form";
import * as $ from 'jquery'
import * as tinymce from 'tinymce'
import 'jquery-ui-timepicker'
import 'jquery-tinymce'
import 'icheck'
import 'select2'

export class DatePickerType extends FormElement
{
    public static apply(element: HTMLElement) : FormElement[]
    {
        let data = [];
        let elements = FormElement.findElements(element, '[data-date-picker]');
        for(element of elements) {
            data.push(new DatePickerType(element));
        }
        return data;
    }

    protected init()
    {
        this.$element.datepicker({
            closeText: 'Fertig',
            dateFormat: 'dd.mm.yy',
            firstDay: 1
        });
    }
}

export class DateTimePickerType extends FormElement
{
    public static apply(element: HTMLElement)
    {
        let data = [];
        let elements = FormElement.findElements(element, '[data-date-time-picker]');
        for(element of elements) {
            data.push(new DateTimePickerType(element));
        }
        return data;
    }

    protected init()
    {
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
    }
}

export class CheckboxType extends FormElement
{
    public static apply(element: HTMLElement)
    {
        let data = [];
        let elements = FormElement.findElements(element, 'input[type=radio],input[type=checkbox]');
        for(element of elements) {
            data.push(new CheckboxType(element));
        }
        return data;
    }

    protected init()
    {
        this.$element.iCheck({
            checkboxClass: 'icheckbox',
            radioClass: 'icheckbox'
        })
    }
}

export class SelectType extends FormElement
{
    public static apply(element: HTMLElement)
    {
        let data = [];
        let elements = FormElement.findElements(element, 'select');
        for(element of elements) {
            data.push(new SelectType(element));
        }
        return data;
    }

    protected init()
    {
        this.$element.select2();
    }
}

export class WysiwygType extends FormElement
{
    public static apply(element: HTMLElement)
    {
        let data = [];
        let elements = FormElement.findElements(element, '[data-wysiwyg]');
        for(element of elements) {
            data.push(new WysiwygType(element));
        }
        return data;
    }

    private generateId(): string {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            let r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    protected init() {
        /**
         * The id of the input field in tiny mce MUST be unique, otherwise it can't be initialized.
         * To make sure it has a unique id, we generate our own at on first initialize
         */
        let id = this.$element.attr('id');
        if(typeof id === 'undefined' || id === null || !id.match(/^wysiwyg_/)) {
            id = 'wysiwyg_' + this.generateId();
            this.$element.attr('id', id);
        }

        let options = {
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
                            src = draggedImg.attr("src")
                        } else {
                            src = draggedImg.attr("largesrc");
                        }
                        ed.execCommand('mceInsertContent', false, "<img src=\"" + src + "\" />");
                    }
                });
            }
        };

        let config : WysiwygConfig = this.$element.data('config');

        if (config.height) {
            options.height = config.height;
        }

        if (config.plugins) {
            options.plugins = config.plugins;
        } else {
            options.plugins = ["advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste autoresize"]
        }

        if (config.style_formats) {
            options.style_formats = config.style_formats;
        }

        if (config.formats) {
            options.formats = config.formats;
        }

        if (config.toolbar1) {
            options.toolbar1 = config.toolbar1
        }

        if (config.toolbar2) {
            options.toolbar2 = config.toolbar2
        }

        if (config.content_css) {
            options.content_css = config.content_css
        }

        this.$element.tinymce(options);
    }

    public destroy() {
        tinymce.remove(this);
    }
}

class WysiwygConfig {
    height: string;
    plugins: string;
    style_formats: string;
    formats: string;
    toolbar1: string;
    toolbar2: string;
    content_css: string;
}

export class ListType extends FormElement
{
    private items : ListItem[];

    private placeholderIndex : number = 0;

    public static apply(element: HTMLElement)
    {
        let data = [];
        let elements = FormElement.findElements(element, '[data-list]');
        for(element of elements) {
            data.push(new ListType(element));
        }
        return data;
    }

    protected init()
    {
        this.initItems();
        this.initAddButton();
    }

    private initItems()
    {
        let self = this;
        this.items = [];
        this.$element.children('[data-list-container]').children().each(function(index, element) {
            self.items.push(new  ListItem(element, self));
        });
    }

    private initAddButton()
    {
        let self = this;
        this.$element.children('[data-add-button]').click(function (event) {
            event.preventDefault();

            let $listContainer = self.$element.children('[data-list-container]');

            // grab the prototype template
            let item = $listContainer.attr('data-prototype');
            let prototype_name = $listContainer.attr('data-prototype-name');

            // generate unique placeholder for reindexing service
            let placeholder = '__name' + self.placeholderIndex + '__';
            self.placeholderIndex++;

            item = item.replace(new RegExp(prototype_name, 'g'), placeholder).trim();
            let initializer = new FormInitializer;
            initializer.setHtml(item);
            initializer.append($listContainer.get(0));
            self.items.push(new ListItem(initializer.getElement(), self));
            self.updatePosition();
        })
    }

    public removeItem(item: ListItem)
    {
        let index = this.items.indexOf(item);
        this.items.splice(index, index);
    }

    public moveItemUp(item: ListItem)
    {
        let index = this.$element.children('[data-list-container]').children().index(item.getElement());

        console.log(index);

        if (index > 0) { // is not first element
            FormDispatcher.dispatchMove(item.getElement());
            let before = this.$element.children('[data-list-container]').children().get(index - 1);
            before.before(item.getElement());
            FormDispatcher.dispatchDrop(item.getElement());
        }

        this.updatePosition();
    }

    public moveItemDown(item: ListItem)
    {
        let index = this.$element.children('[data-list-container]').children().index(item.getElement());
        let size = this.$element.children('[data-list-container]').children().length;

        if (index < (size - 1)) { // is not first element
            FormDispatcher.dispatchMove(item.getElement());
            let after = this.$element.children('[data-list-container]').children().get(index + 1);
            after.after(item.getElement());
            FormDispatcher.dispatchDrop(item.getElement());
        }

        this.updatePosition();
    }

    private updatePosition()
    {
        let i = 0;
        for(let item of this.items) {
            i++;
            item.setPosition(i);
        }
    }
}

class ListItem
{
    private $element: JQuery;

    private $buttons: JQuery;

    private list: ListType;

    constructor(element: HTMLElement, list: ListType)
    {
        this.$element = $(element);
        this.$buttons = this.$element.children('[data-list-item-buttons]');
        this.list = list;
        this.initDeleteButton();
        this.initUpButton();
        this.initDownButton();
    }

    private initDeleteButton()
    {
        let self = this;
        this.$buttons.children('[data-list-item-delete]').click(function(event) {
            self.$element.remove();
        });
    }

    private initUpButton()
    {
        let self = this;
        this.$buttons.children('[data-list-item-up]').click(function(event) {
            event.preventDefault();
            event.stopPropagation();
            self.list.moveItemUp(self);
        });
    }

    private initDownButton()
    {
        let self = this;
        this.$buttons.children('[data-list-item-down]').click(function(event) {
            event.preventDefault();
            event.stopPropagation();
            self.list.moveItemDown(self);
        });
    }

    public getElement()
    {
        return this.$element.get(0);
    }

    public setPosition(number: number)
    {
        this.$element.find('[data-position]').val(number);
    }
}
