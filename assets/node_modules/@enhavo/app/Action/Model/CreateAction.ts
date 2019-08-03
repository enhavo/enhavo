import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class CreateAction extends AbstractAction
{
    label: string;
    url: string;

    execute(): void
    {
        this.application.getView().open(this.url, 'edit-view');
    }
}