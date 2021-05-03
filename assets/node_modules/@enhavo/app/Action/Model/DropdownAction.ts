import ActionInterface from "@enhavo/app/Action/ActionInterface";
import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class DropdownAction extends AbstractAction
{
    items: ActionInterface[];
    closeAfter: boolean;

    execute(): void
    {
    }
}