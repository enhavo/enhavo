import {WysiwygMenuButton} from "@enhavo/form/wysiwyg/menu/WysiwygMenuButton";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";

export class WysiwygColorButton extends WysiwygMenuButton
{
    component: string = 'form-wysiwyg-color-button';

    labelClear: string | ( (form: WysiwygForm) => string ) = 'enhavo_form.wysiwyg_form.command.color.label_clear';
    labelCustom: string | ( (form: WysiwygForm) => string ) = 'enhavo_form.wysiwyg_form.command.color.label_custom';
    translationDomain = 'javascript';

    public selectedColor: string = '#e03e2d';
    public customColor: string = null;
    public colorPalette: string[] = [
        '#bfedd2',
        '#fbeeb8',
        '#f8cac6',
        '#eccafa',
        '#c2e0f4',
        '#2dc26b',
        '#f1c40f',
        '#e03e2d',
        '#b96ad9',
        '#3598db',
        '#169179',
        '#e67e23',
        '#ba372a',
        '#843fa1',
        '#236fa1',
        '#ecf0f1',
        '#ced4d9',
        '#95a5a6',
        '#7e8c8d',
        '#34495e',
        '#000000',
        '#ffffff',
    ];

    click: (event: Event, form: WysiwygForm) => void = (event: Event, form: WysiwygForm) => {
        if (this.selectedColor === null) {
            this.applyClearColor(form);
        } else {
            this.applySelectedColor(form);
        }
    }

    public applySelectedColor(form: WysiwygForm)
    {
        form.editor.chain().focus().setColor(this.selectedColor).run();
    }

    public applyClearColor(form: WysiwygForm)
    {
        form.editor.chain().focus().unsetColor().run();
    }

    public getLabelClear(form: WysiwygForm)
    {
        if (this.labelClear !== null) {
            if (typeof this.labelClear === 'string') {
                return this.labelClear;
            } else {
                return this.labelClear(form);
            }
        }
        return null;
    }

    public getLabelCustom(form: WysiwygForm)
    {
        if (this.labelCustom !== null) {
            if (typeof this.labelCustom === 'string') {
                return this.labelCustom;
            } else {
                return this.labelCustom(form);
            }
        }
        return null;
    }
}
