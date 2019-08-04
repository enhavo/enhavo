import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class DropdownAction extends AbstractAction
{
    execute(): void
    {
        console.log('dropdown');
    }
}