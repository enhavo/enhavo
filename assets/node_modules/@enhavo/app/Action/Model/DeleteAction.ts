import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class DeleteAction extends AbstractAction
{
    execute(): void
    {
        console.log('delete');
    }
}