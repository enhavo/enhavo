import AbstractColumn from "@enhavo/app/Grid/Column/Model/AbstractColumn";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default class ActionColumn extends AbstractColumn
{
    private registry: ActionRegistry;
    public action: ActionInterface;

    constructor(registry: ActionRegistry)
    {
        super();
        this.registry = registry;
    }

    getAction(data: any) {
        return this.registry.getFactory(data.component).createFromData(data);
    }
}