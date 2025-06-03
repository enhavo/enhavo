import {AbstractTab} from "./AbstractTab";
import {Form} from "@enhavo/vue-form/model/Form";

export class FormTab extends AbstractTab
{
    public form;
    public arrangement;

    update(parameters: object): void
    {
        this.error = false;
        if (parameters.hasOwnProperty('form') && this.arrangement) {
            for (let row of this.arrangement) {
                for (let column of row) {
                    if (parameters.form.has(column.key)) {
                        if (this.formHasErrors(parameters.form.get(column.key))) {
                            this.error = true;
                            return;
                        }
                    }
                }
            }
        }
    }

    protected formHasErrors(form: Form): boolean
    {
        if (form.errors && form.errors.length > 0) {
            return true;
        }

        for(let child of form.children) {
            if (this.formHasErrors(child)) {
                return true;
            }
        }

        return false;
    }
}
