import ActionInterface from "@enhavo/app/Action/ActionInterface";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import * as _ from 'lodash';

export default class ActionManager
{
    private actions: ActionInterface[];
    private registry: ActionRegistry;

    constructor(actions: ActionInterface[], registry: ActionRegistry)
    {
        this.registry = registry;
        for (let i in actions) {
            let action = registry.getFactory(actions[i].component).createFromData(actions[i]);
            _.extend(actions[i], action);
        }
        this.actions = actions;
    }
}