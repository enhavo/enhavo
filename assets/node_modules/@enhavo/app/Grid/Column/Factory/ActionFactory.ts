import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import ActionColumn from "@enhavo/app/Grid/Column/Model/ActionColumn";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";

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
