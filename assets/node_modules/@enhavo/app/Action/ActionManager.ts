import registry from "@enhavo/app/Action/registry";
import ActionInterface from "@enhavo/app/Action/ActionInterface";
import * as _ from 'lodash';

export default class ActionManager
{
    private actions: ActionInterface[];

    constructor(actions: ActionInterface[])
    {
        for (let i in actions) {
            let action = registry.getFactory(actions[i].component).createFromData(actions[i]);
            _.extend(actions[i], action);
        }
        this.actions = actions;
    }
}