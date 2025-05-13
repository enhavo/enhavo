import {AbstractFormVisitor} from "@enhavo/vue-form/form/FormVisitor"
import {Form} from "@enhavo/vue-form/model/Form"

export class AdminFormVisitor extends AbstractFormVisitor
{
    supports(form: Form): boolean
    {
        return true;
    }

    apply(form: Form): Form | void
    {
        if (form.widgetComponent === 'form-widget') {
            form.widgetComponent = 'form-admin-widget';
        }

        if (form.rowComponent === 'form-row') {
            form.rowComponent = 'form-admin-row';
        }
    }
}
