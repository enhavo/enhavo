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

    getAction(data: any): ActionInterface
    {
        return this.actionManager.createAction(data);
    }
}
