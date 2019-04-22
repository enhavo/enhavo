import FormType from "@enhavo/form/FormType";
import * as $ from "jquery";
import * as tinymce from "tinymce";
import 'tinymce/themes/silver/theme';
import WysiwygConfig from "@enhavo/form/Type/WysiwygConfig";

export default class WysiwygType extends FormType
{
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
            target: this.$element.get(0),
            menubar: false,
            branding: false,
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
            oninit: function (ed: any) {
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

        tinymce.EditorManager.init(options);
    }
}
