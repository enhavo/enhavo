import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {Translator} from "@enhavo/app/translation/Translator";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";

export class DuplicateAction extends AbstractAction
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
        private readonly resourceInputManager: ResourceInputManager,
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
                this.duplicateResource();
            }
        });
    }

    private async duplicateResource()
    {
        this.uiManager.loading(true);

        const response = await fetch(this.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ token: this.token }),
        });

        if (response.ok) {
            let data = await response.json();

            this.uiManager.loading(false);
            this.frameManager.dispatch(new Event('input_changed'));
            this.flashMessenger.success(this.translator.trans('enhavo_app.duplicate.message.success', {}, 'javascript'));

            (await this.frameManager.getFrame()).loaded = false;
            this.uiManager.loading(false);
            window.location.href = data.redirect;

            // ToDo: Better load data from api and replace, but have to solve form issues first
            // await this.resourceInputManager.redirect(data.redirect);
            // await this.resourceInputManager.load(data.url);
        } else {
            this.uiManager.loading(false);
            this.flashMessenger.error(this.translator.trans('enhavo_app.error', {}, 'javascript'));
        }
    }
}
