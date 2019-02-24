import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class SaveAction extends AbstractAction
{
    execute(): void
    {
        console.log('save');
    }
}