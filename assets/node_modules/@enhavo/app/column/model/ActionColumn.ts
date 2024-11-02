import {AbstractColumn} from "@enhavo/app/column/model/AbstractColumn";
import {ActionManager} from "@enhavo/app/action/ActionManager";
import {ActionInterface} from "../../action/ActionInterface";

export class ActionColumn extends AbstractColumn
{
    constructor(
        private actionManager: ActionManager
    )
    {
        super();
    }

    getActions(actions: []): ActionInterface[]
    {
        return this.actionManager.createActions(actions);
    }
}

export class ActionColumnData
{
    actions: ActionInterface[];
}
