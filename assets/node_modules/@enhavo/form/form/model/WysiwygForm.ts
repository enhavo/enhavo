import {Form} from "@enhavo/vue-form/model/Form";
import tinymce from 'tinymce/tinymce';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/skins/ui/oxide/skin.css';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/code';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/emoticons/js/emojis';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/table';
import {MoveEvent} from "@enhavo/form/form/event/MoveEvent";

export class WysiwygForm extends Form
{
    public editorId: any = null;
    public editorElement: HTMLElement;

    public init()
    {
        // https://github.com/tinymce/tinymce/issues/1073
        this.eventDispatcher.addListener('move', (event: MoveEvent) => {
            this.reinitWysiwyg()
        });

        this.editorId = this.generateId();
    }

    public destroy()
    {
        super.destroy();
        tinymce.EditorManager.remove('#'+this.editorElement.id);
    }

    public initWysiwyg()
    {
        // if init is called to early after a remove was triggered, tinymce will not initialize, so we have to wait a few milli seconds
        window.setTimeout(() => {
            tinymce.init(this.getSettings());
        }, 10)
    }

    public removeWysiwyg()
    {
        tinymce.EditorManager.remove('#'+this.editorElement.id);
    }

    public reinitWysiwyg()
    {
        this.removeWysiwyg();
        this.initWysiwyg();
    }

    private getSettings()
    {
        return {
            base_url: "/build/enhavo", // Because we use dynamic imports, we need to specify the base path to prevent a loading bug in firefox (https://github.com/tinymce/tinymce-docs/issues/1466)
            toolbar1: "undo redo | styleselect bold italic underline | forecolor backcolor | link | alignleft aligncenter alignright alignjustify | outdent indent | bullist numlist | code",
            menubar: false,
            branding: false,
            force_br_newlines: false,
            force_p_newlines: false,
            forced_root_block: "p",
            cleanup: false,
            target: this.editorElement,
            cleanup_on_startup: false,
            font_size_style_values: "xx-small,x-small,small,medium,large,x-large,xx-large",
            convert_fonts_to_spans: true,
            resize: false,
            relative_urls: false,
            remove_script_host:false,
            plugins: ["advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table paste autoresize"],
            // content_css: editorCss,
            min_height: 160,
            autoresize_on_init: false,
            autoresize_max_height: 1000,
            contextmenu: "",
            setup: (editor: any) => {
                editor.on('init', () => {
                    editor.setContent(this.value);
                });
                editor.on('keyup change', () => {
                    this.value = editor.getContent();
                });
            }
        };
    }

    private generateId(): string
    {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            let r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
}
