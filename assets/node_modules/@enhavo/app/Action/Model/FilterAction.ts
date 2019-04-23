import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class FilterAction extends AbstractAction
{
    execute(): void
    {
        console.log('filter');
    }
}