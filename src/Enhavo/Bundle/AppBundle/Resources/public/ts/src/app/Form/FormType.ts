import { FormElement } from "app/Form/Form";
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


//
// class List
// {
//     init() {
//         this.initList = function (form) {
//
//             var initDeleteButton = function (item) {
//                 $(item).first().find('.button-delete').click(function (e) {
//                     e.preventDefault();
//                     $(this).closest('.listElement').remove();
//                     self.reindex();
//                 });
//             };
//
//             var initAddButton = function (list) {
//                 list.parents('[data-list-container]').first().children('[data-add-button]').click(function (e) {
//                     e.preventDefault();
//
//                     var $formWidget = $(this).parents('[data-list-container]').first();
//                     var $listContainer = $formWidget.children('[data-list-container]');
//
//                     // grab the prototype template
//                     var item = $listContainer.attr('data-prototype');
//                     var prototype_name = $listContainer.attr('data-prototype-name');
//
//                     // Generate unique placeholder for reindexing service
//                     var placeholder = '__name' + placeholderIndex + '__';
//                     placeholderIndex++;
//
//                     // replace prototype_name used in id and name with placeholder
//                     item = item.replace(new RegExp(prototype_name, 'g'), placeholder);
//                     item = $.parseHTML(item.trim());
//
//                     // Initialize sub-elements for reindexing
//                     self.initReindexableItem(item, placeholder);
//
//                     $listContainer.append(item);
//                     initItem(item);
//                     $(document).trigger('formListAddItem', item);
//                     setOrderForContainer(list);
//                     self.reindex();
//                 })
//             };
//
//             var initItem = function (item) {
//                 initButtonUp(item);
//                 initButtonDown(item);
//                 initDeleteButton(item);
//             };
//
//             var initButtonUp = function (item) {
//                 $(item).on('click', '.button-up', function (event) {
//                     event.preventDefault();
//                     event.stopPropagation();
//
//                     var liElement = $(this).parent();
//                     while (!liElement.hasClass('listElement')) {
//                         liElement = liElement.parent();
//                     }
//                     var list = liElement.parent();
//                     var index = list.children().index(liElement);
//
//                     if (index > 0) { // is not first element
//                         if (liElement.find('[data-wysiwyg]').length) {
//                             self.destroyWysiwyg(liElement);
//                             $(list.children().get(index - 1)).before(liElement); //move element before last
//                             self.initWysiwyg(liElement);
//                         } else {
//                             $(list.children().get(index - 1)).before(liElement); //move element before last
//                         }
//                     }
//
//                     setOrderForContainer(list);
//                     self.reindex();
//                 });
//             };
//
//             var initButtonDown = function (item) {
//                 $(item).on('click', '.button-down', function (event) {
//                     event.preventDefault();
//                     event.stopPropagation();
//
//                     var liElement = $(this).parent();
//                     while (!liElement.hasClass('listElement')) {
//                         liElement = liElement.parent();
//                     }
//                     var list = liElement.parent();
//                     var index = list.children().index(liElement);
//                     var size = list.children().length;
//
//                     if (index < (size - 1)) { // is not last element
//                         if (liElement.find('[data-wysiwyg]').length) {
//                             self.destroyWysiwyg(liElement);
//                             $(list.children().get(index + 1)).after(liElement); //move element after next
//                             self.initWysiwyg(liElement);
//                         } else {
//                             $(list.children().get(index + 1)).after(liElement); //move element after next
//                         }
//                     }
//
//                     setOrderForContainer(list);
//                     self.reindex();
//                 });
//             };
//
//             var setOrderForContainer = function (list) {
//                 var orderby = list.attr('data-order');
//                 list.find("." + orderby).each(function (index) {
//                     $(this).val(index + 1);
//                 });
//             };
//
//             (function (form) {
//                 $(form).find('[data-list-container]').each(function () {
//                     var list = $(this);
//
//                     if (typeof list.attr('data-reindexable') != 'undefined') {
//                         // Save initial index
//                         list.data('initial-list-index', list.children().length);
//                     }
//
//                     $.each(list.children(), function (index, item) {
//                         initItem($(item));
//                     });
//
//                     setOrderForContainer(list);
//                     initAddButton(list);
//                 });
//             })(form);
//         };
//     }
// }