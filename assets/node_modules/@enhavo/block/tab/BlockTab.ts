import {AbstractTab} from "@enhavo/app/tab/model/AbstractTab";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {ActionManager} from "@enhavo/app/action/ActionManager";

export class BlockTab extends AbstractTab
{
    public property: string;
    public actions: ActionInterface[];

    constructor(
        private actionManager: ActionManager
    ) {
        super();
    }

    init()
    {
        this.actions = this.actionManager.createActions(this.actions);
    }
}
