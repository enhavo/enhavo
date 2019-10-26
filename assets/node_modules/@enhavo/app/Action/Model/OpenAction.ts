import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class OpenAction extends AbstractAction
{
    public url: string;
    public target: string;

    execute(): void
    {
        if(this.target == '_view') {
            this.application.getView().open(this.url);
            return;
        }
        window.open(this.url, this.target);
    }
}