import {WysiwygMenuItem} from "@enhavo/form/wysiwyg/WysiwygMenuItem";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";

export class WysiwygMenuButton extends WysiwygMenuItem
{
    component: string = 'form-wysiwyg-menu-button';

    label: string | ( (form: WysiwygForm) => string ) = null;
    icon: string | ( (form: WysiwygForm) => string ) = null;
    tooltip: string | ( (form: WysiwygForm) => string ) = null;
    translationDomain: string | ( (form: WysiwygForm) => string ) = null;
    disabled: boolean | ( (form: WysiwygForm) => boolean ) = false;
    class: string | ( (form: WysiwygForm) => string ) = '';

    click: (event: Event, form: WysiwygForm) => void = null;


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

    public getDisabled(form: WysiwygForm): boolean
    {
        if (typeof this.disabled === 'boolean') {
            return this.disabled;
        } else {
            return this.disabled(form);
        }
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
