import { ActionInterface } from "@enhavo/app/action/ActionInterface";
import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import { ActionManager } from "@enhavo/app/action/ActionManager";

export class DropdownAction extends AbstractAction
{
    isOpen: boolean = false;
    items: ActionInterface[];
    closeAfter: boolean;

    constructor(
        private readonly actionManager: ActionManager
    ) {
        super();
    }

    onInit()
    {
        this.items = this.actionManager.createActions(this.items);
    }

    execute(): void
    {
        this.toggle();
    }

    toggle()
    {
        this.isOpen = !this.isOpen;
    }

    open()
    {
        this.isOpen = true;
    }

    close()
    {
        this.isOpen = false;
    }
}
