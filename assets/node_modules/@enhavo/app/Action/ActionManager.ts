import ActionInterface from "@enhavo/app/Action/ActionInterface";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import * as _ from 'lodash';

export default class ActionManager
{
    private primary: ActionInterface[];
    private secondary: ActionInterface[];
    private registry: ActionRegistry;

    constructor(primary: ActionInterface[], secondary: ActionInterface[], registry: ActionRegistry)
    {
        this.registry = registry;

        for (let i in primary) {
            let action = registry.getFactory(primary[i].component).createFromData(primary[i]);
            _.extend(primary[i], action);
        }
        this.primary = primary;

        for (let i in secondary) {
            let action = registry.getFactory(secondary[i].component).createFromData(secondary[i]);
            _.extend(secondary[i], action);
        }
        this.secondary = secondary;
    }
}