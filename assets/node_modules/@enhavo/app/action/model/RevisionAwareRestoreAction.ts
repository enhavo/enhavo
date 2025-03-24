import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Translator} from "@enhavo/app/translation/Translator";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {ClientInterface} from "@enhavo/app/client/ClientInterface";

export class RevisionAwareRestoreAction extends AbstractAction
{
    public url: string;
    public token: string;
    public reload: boolean;

    public confirmMessage: string;
    public confirmLabelOk: string;
    public confirmLabelCancel: string;

    constructor(
        private readonly uiManager: UiManager,
        private readonly flashMessenger: FlashMessenger,
        private readonly frameManager: FrameManager,
        private readonly translator: Translator,
        private readonly resourceInputManager: ResourceInputManager,
        private readonly client: ClientInterface,
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

        let transport = await this.client.fetch(this.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                token: this.token
            }),
        });

        if (!transport.ok || !transport.response.ok) {
            this.uiManager.loading(false);
            await this.client.handleError(transport, {
                confirm: true
            });
            return;
        }

        this.frameManager.dispatch(new Event('input_changed'));
        this.flashMessenger.success(this.translator.trans('enhavo_app.revision.message.restored', {}, 'javascript'));
        if (this.reload) {
            (await this.frameManager.getFrame()).loaded = false;
            this.uiManager.loading(false);
            window.location.reload();
        }
        this.uiManager.loading(false);
    }
}
