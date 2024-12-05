import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuItem} from "@enhavo/form/wysiwyg//WysiwygMenuItem";

export class WysiwygMenuSubmenu extends WysiwygMenuItem
{
    component: string = 'form-wysiwyg-menu-submenu';

    label: string | ( (form: WysiwygForm) => string ) = null;
    icon: string | ( (form: WysiwygForm) => string ) = null;
    tooltip: string | ( (form: WysiwygForm) => string ) = 'asd';
    class: string | ( (form: WysiwygForm) => string ) = '';

    click: (event: Event, form: WysiwygForm) => {} = null;

    items: WysiwygMenuItem[] = [];
}
