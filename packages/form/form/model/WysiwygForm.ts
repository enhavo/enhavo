import {Form} from "@enhavo/vue-form/model/Form";
import {WysiwygConfigInterface} from "@enhavo/form/wysiwyg/menu/WysiwygConfigInterface";
import {WysiwygModalConfiguration} from "@enhavo/form/wysiwyg/menu/WysiwygModalConfiguration";
import WysiwygConfigRegistry from "@enhavo/form/wysiwyg/menu/WysiwygConfigRegistry";
import {Editor} from '@tiptap/vue-3';

export class WysiwygForm extends Form
{
    public editor: Editor;
    public additionalCssClasses: string[];

    public configName: string = 'default';
    public config: WysiwygConfigInterface;

    public modal: WysiwygModalConfiguration;

    public searchAndReplaceOpen: boolean;
    public searchAndReplaceSearchTerm: string;
    public searchAndReplaceReplaceTerm: string;

    public constructor(
        private readonly configRegistry: WysiwygConfigRegistry,
    ) {
        super();
    }

    init()
    {
        this.editor = null;
        this.additionalCssClasses = [];
        this.config = null;
        this.modal = null;
        this.searchAndReplaceOpen = false;
        this.searchAndReplaceSearchTerm = '';
        this.searchAndReplaceReplaceTerm = '';
    }

    initEditor()
    {
        if (this.editor !== null) {
            return;
        }

        this.editor = new Editor({
            content: this.value,
            extensions: this.getConfig().extensions,
            onUpdate: ({ editor }) => {
                this.value = editor.getHTML();
                this.dispatchChange();
            },
        });
    }

    destroyEditor()
    {
        if (this.editor) {
            this.editor.destroy();
            this.editor = null;
        }
    }

    update(recursive: boolean = true)
    {
        super.update(recursive);
        this.destroyEditor();
        this.initEditor();
    }

    getConfig(): WysiwygConfigInterface
    {
        if (this.config === null) {
            this.config = this.configRegistry.getConfig(this.configName);
        }
        return this.config;
    }

    openModal(component: string, options: object = {}): Promise<object>
    {
        this.addCssClass('modal-open');
        this.modal = new WysiwygModalConfiguration(component, options);
        return new Promise<object>((resolve, reject) => {
            this.modal.getPromise()
                .then((result) => {
                    resolve(result);
                    this.modal = null;
                })
                .catch(() => {
                    reject();
                    this.modal = null;
                })
                .finally(() => {
                    this.removeCssClass('modal-open');
                })
            ;
        });
    }

    addCssClass(cssClass: string)
    {
        if (this.additionalCssClasses.indexOf(cssClass) === -1) {
            this.additionalCssClasses.push(cssClass);
        }
    }

    removeCssClass(cssClass: string)
    {
        const index = this.additionalCssClasses.indexOf(cssClass);
        if (index > -1) {
            this.additionalCssClasses.splice(index, 1);
        }
    }
}
