import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class OpenAction extends AbstractAction
{
    public url: string;
    public target: string;

    execute(): void
    {
        window.open(this.url, this.target);
    }
}