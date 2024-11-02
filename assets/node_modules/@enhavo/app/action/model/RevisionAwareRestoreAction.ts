import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Translator} from "@enhavo/app/translation/Translator";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";

export class RevisionAwareRestoreAction extends AbstractAction
{
    public url: string;
    public token: string;

    public confirmMessage: string;
    public confirmLabelOk: string;
    public confirmLabelCancel: string;

    constructor(
        private readonly uiManager: UiManager,
        private readonly flashMessenger: FlashMessenger,
        private readonly frameManager: FrameManager,
        private readonly translator: Translator,
    ) {
        super();
    }

    async execute(): Promise<void>
    {
        this.uiManager.confirm({
            message: this.confirmMessage,
            denyLabel: this.confirmLabelCancel,
            acceptLabel: this.confirmLabelOk,
        }).then((accept: boolean) => {
            if (accept) {
                this.restore();
            }
        });
    }

    private async restore()
    {
        this.uiManager.loading(true);

        let response = await fetch(this.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                token: this.token
            }),
        });

        if (response.ok) {
            this.frameManager.dispatch(new Event('input_changed'));
            this.flashMessenger.success(this.translator.trans('enhavo_app.revision.message.restored', {}, 'javascript'));
            this.uiManager.loading(false);
        } else {
            this.uiManager.loading(false);
            this.flashMessenger.error(this.translator.trans('enhavo_app.error', {}, 'javascript'));
        }
    }
}
