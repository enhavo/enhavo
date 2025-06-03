import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {Form} from "@enhavo/vue-form/model/Form";
import {PolyCollectionForm} from "@enhavo/form/model/PolyCollectionForm";

export class BlockCollapseAction extends AbstractAction
{
    public collapsed: boolean = false;
    public property: string;

    constructor(
        private readonly resourceInputManager: ResourceInputManager,
    ) {
        super();
    }

    execute(): void
    {
        this.collapsed = !this.collapsed;
        let form = this.resourceInputManager.form.get(this.property);

        this.executeOnForm(form);

        return;

    }

    private executeOnForm(form: Form)
    {
        for (let child of form.children) {
            this.executeOnForm(child);
        }

        if (typeof form['collapseAll'] === 'function' && typeof form['uncollapseAll'] === 'function') {
            if (this.collapsed) {
                (form as PolyCollectionForm).collapseAll();
            } else {
                (form as PolyCollectionForm).uncollapseAll();
            }
        }
    }
}