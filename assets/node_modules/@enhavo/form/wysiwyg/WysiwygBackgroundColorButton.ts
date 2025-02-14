import {WysiwygColorButton} from "@enhavo/form/wysiwyg/WysiwygColorButton";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";

export class WysiwygBackgroundColorButton extends WysiwygColorButton
{
    icon = 'create';

    labelClear = 'enhavo_form.wysiwyg_form.command.background_color.label_clear';
    labelCustom = 'enhavo_form.wysiwyg_form.command.background_color.label_custom';

    public applySelectedColor(form: WysiwygForm)
    {
        form.editor.chain().focus().setBackgroundColor(this.selectedColor).run();
    }

    public applyClearColor(form: WysiwygForm)
    {
        form.editor.chain().focus().unsetBackgroundColor().run();
    }
}
