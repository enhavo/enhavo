import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {UiManager} from "@enhavo/app/ui/UiManager";

export class OpenAction extends AbstractAction
{
    public url: string;
    public target: string;
    public key: string;

    public confirm: boolean;
    public confirmMessage: string;
    public confirmLabelOk: string;
    public confirmLabelCancel: string;

    constructor(
        private readonly frameManager: FrameManager,
        private readonly uiManager: UiManager,
    ) {
        super();
    }

    execute(): void
    {
        if (this.confirm) {
            this.uiManager.confirm({
                message: this.confirmMessage,
                denyLabel: this.confirmLabelCancel,
                acceptLabel: this.confirmLabelOk,
            }).then((accept: boolean) => {
                if (accept) {
                    this.open();
                }
            });
        } else {
            this.open();
        }
    }

    private open()
    {
        if (this.target == '_view') {
            this.frameManager.openFrame({
                url: this.url,
                key: this.key,
            }).then();
        } else {
            window.open(this.url, this.target);
        }
    }
}
