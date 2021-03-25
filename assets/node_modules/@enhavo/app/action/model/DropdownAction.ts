import AbstractAction from "@enhavo/app/action/model/AbstractAction";

export default class DropdownAction extends AbstractAction
{
    execute(): void
    {
        console.log('dropdown');
    }
}