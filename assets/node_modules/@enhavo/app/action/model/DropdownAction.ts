import ActionInterface from "@enhavo/app/action/ActionInterface";
import AbstractAction from "@enhavo/app/action/model/AbstractAction";

export default class DropdownAction extends AbstractAction
{
    items: ActionInterface[];
    closeAfter: boolean;

    execute(): void
    {
    }
}