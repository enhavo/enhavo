import {WysiwygMenuButton} from "@enhavo/form/wysiwyg/menu/WysiwygMenuButton";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";

export class WysiwygSourceCodeButton extends WysiwygMenuButton
{
    component: string = 'form-wysiwyg-menu-button';

    label: string | ( (form: WysiwygForm) => string ) = null;
    icon: string | ( (form: WysiwygForm) => string ) = 'code';
    tooltip: string | ( (form: WysiwygForm) => string ) = null;
    disabled: boolean | ( (form: WysiwygForm) => boolean ) = false;
    class: string | ( (form: WysiwygForm) => string ) = '';

    click: (event: Event, form: WysiwygForm) => void =
        (event: Event, form: WysiwygForm) => {
            form.openModal('form-wysiwyg-modal-source-code', {
                    code: WysiwygSourceCodeButton.addWhitespace(form.editor.getHTML()),
                })
                .then((result: object) => {
                    form.destroyEditor();
                    form.value = result['code'];
                    form.initEditor();
                })
                .catch(() => {
                    // Cancelled
                })
            ;
        };

    private static addWhitespace(code)
    {
        let result = code.replaceAll(/<([^\/>]+)>/g, '\n<$1>');
        result = result.replaceAll(/<\/([^>]+)>/g, '</$1>\n');
        return result;
    }
}
