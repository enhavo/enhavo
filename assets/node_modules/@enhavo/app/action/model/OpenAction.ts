import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import Confirm from "@enhavo/app/view/Confirm";
import View from "@enhavo/app/view/View";

export class OpenAction extends AbstractAction
{
    public url: string;
    public target: string;
    public key: string;

    public confirm: boolean;
    public confirmMessage: string;
    public confirmLabelOk: string;
    public confirmLabelCancel: string;

    private readonly view: View;

    constructor(view: View) {
        super();
        this.view = view;
    }

    execute(): void
    {
        if (this.confirm) {
            this.view.confirm(new Confirm(
                this.confirmMessage,
                () => {
                    this.open();
                },
                () => {},
                this.confirmLabelCancel,
                this.confirmLabelOk,
            ));
        } else {
            this.open();
        }
    }

    private open()
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