import AbstractFactory from "@enhavo/app/grid/column/factory/AbstractFactory";
import ActionColumn from "@enhavo/app/grid/column/model/ActionColumn";
import ActionRegistry from "@enhavo/app/action/ActionRegistry";

export default class ActionFactory extends AbstractFactory
{
    private readonly registry: ActionRegistry;

    constructor(registry: ActionRegistry)
    {
        super();
        this.registry = registry;
    }

    createNew(): ActionColumn {
        return new ActionColumn(this.registry);
    }
}
