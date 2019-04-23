import AbstractColumn from "@enhavo/app/Grid/Column/Model/AbstractColumn";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default class ActionColumn extends AbstractColumn
{
    private registry: ActionRegistry;
    public mapping: string;
    public action: ActionInterface;
    private actionLoaded: boolean = false;

    constructor(registry: ActionRegistry)
    {
        super();
        this.registry = registry;
    }

    getAction() {
        if(!this.actionLoaded) {
            this.action = this.registry.getFactory(this.action.component).createFromData(this.action);
            this.actionLoaded = true;
        }
        return this.action;
    }
}