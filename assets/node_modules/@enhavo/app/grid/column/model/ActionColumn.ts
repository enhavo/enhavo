import AbstractColumn from "@enhavo/app/grid/column/model/AbstractColumn";
import ActionRegistry from "@enhavo/app/action/ActionRegistry";
import ActionInterface from "@enhavo/app/action/ActionInterface";

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