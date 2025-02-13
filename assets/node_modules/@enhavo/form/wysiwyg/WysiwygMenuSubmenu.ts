import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuItem} from "@enhavo/form/wysiwyg//WysiwygMenuItem";

export class WysiwygMenuSubmenu extends WysiwygMenuItem
{
    component: string = 'form-wysiwyg-menu-submenu';

    label: string | ( (form: WysiwygForm) => string ) = null;
    icon: string | ( (form: WysiwygForm) => string ) = null;
    tooltip: string | ( (form: WysiwygForm) => string ) = null;
    translationDomain: string | ( (form: WysiwygForm) => string ) = null;
    class: string | ( (form: WysiwygForm) => string ) = '';

    click: (event: Event, form: WysiwygForm) => {} = null;

    items: WysiwygMenuItem[] = [];


    public getLabel(form: WysiwygForm)
    {
        if (this.label !== null) {
            if (typeof this.label === 'string') {
                return this.label;
            } else {
                return this.label(form);
            }
        }
        return null;
    }

    public getTooltip(form: WysiwygForm)
    {
        if (this.tooltip !== null) {
            if (typeof this.tooltip === 'string') {
                return this.tooltip;
            } else {
                return this.tooltip(form);
            }
        }
        return null;
    }

    public getTranslationDomain(form: WysiwygForm): string
    {
        if (this.translationDomain !== null) {
            if (typeof this.translationDomain === 'string') {
                return this.translationDomain;
            } else {
                return this.translationDomain(form);
            }
        }
        return null;
    }

    public getClass(form: WysiwygForm)
    {
        if (this.class !== null) {
            if (typeof this.class === 'string') {
                return this.class;
            } else {
                return this.class(form);
            }
        }
        return null;
    }
}
