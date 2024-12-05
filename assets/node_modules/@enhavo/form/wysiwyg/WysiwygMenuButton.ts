import {WysiwygMenuItem} from "@enhavo/form/wysiwyg//WysiwygMenuItem";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";

export class WysiwygMenuButton extends WysiwygMenuItem
{
    component: string = 'form-wysiwyg-menu-button';

    label: string | ( (form: WysiwygForm) => string ) = null;
    icon: string | ( (form: WysiwygForm) => string ) = null;
    tooltip: string | ( (form: WysiwygForm) => string ) = null;
    disabled: boolean | ( (form: WysiwygForm) => boolean ) = false;
    class: string | ( (form: WysiwygForm) => string ) = '';

    click: (event: Event, form: WysiwygForm) => {} = null;
}
