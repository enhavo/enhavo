import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class PreviewAction extends AbstractAction
{
    execute(): void
    {
        console.log('preview');
    }
}