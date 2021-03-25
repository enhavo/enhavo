import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import View from "@enhavo/app/View/View";

export default class OpenAction extends AbstractAction
{
    public url: string;
    public target: string;
    public key: string;

    private readonly view: View;

    constructor(view: View) {
        super();
        this.view = view;
    }

    execute(): void
    {
        if(this.target == '_view') {
            if(this.key) {
                this.view.open(this.url, this.key);
            } else {
                this.view.open(this.url);
            }
            return;
        }
        window.open(this.url, this.target);
    }
}