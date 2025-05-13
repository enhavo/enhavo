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
        if (form.component === 'form-radio') {
            form.component = 'form-icheck-radio';
        }

        if (form.component === 'form-checkbox') {
            form.component = 'form-icheck-checkbox';
        }

        if (form.component === 'form-choice') {
            form.component = 'form-admin-choice';
        }
    }
}
