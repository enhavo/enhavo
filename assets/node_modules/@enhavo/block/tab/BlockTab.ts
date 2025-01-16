import {AbstractTab} from "@enhavo/app/tab/model/AbstractTab";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {ActionManager} from "@enhavo/app/action/ActionManager";
import {Form} from "@enhavo/vue-form/model/Form";

export class BlockTab extends AbstractTab
{
    public property: string;
    public actions: ActionInterface[];

    constructor(
        private actionManager: ActionManager
    ) {
        super();
    }

    init()
    {
        this.actions = this.actionManager.createActions(this.actions);
    }

    update(parameters: object): void
    {
        this.error = false;
        if (parameters.hasOwnProperty('form')) {
            if (parameters.form.has(this.property)) {
                if (this.formHasErrors(parameters.form.get(this.property))) {
                    this.error = true;
                    return;
                }
            }
        }
    }

    private formHasErrors(form: Form): boolean
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
