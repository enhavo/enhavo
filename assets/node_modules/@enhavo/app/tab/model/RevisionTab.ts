import {AbstractTab} from "./AbstractTab";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Translator} from "@enhavo/app/translation/Translator";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import {TabInterface} from "@enhavo/app/tab/TabInterface";

export class RevisionTab extends AbstractTab
{
    public revisions: Revision[];
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

    async activateRevision(revision: Revision)
    {
        this.uiManager.confirm({
            message: this.confirmMessage,
            denyLabel: this.confirmLabelCancel,
            acceptLabel: this.confirmLabelOk,
        }).then((accept: boolean) => {
            if (accept) {
                this.sendActivate(revision);
            }
        });
    }

    private async sendActivate(revision: Revision)
    {
        this.uiManager.loading(true);

        let response = await fetch(revision.url, {
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
            await this.resourceInputManager.load(this.resourceInputManager.url);
            this.uiManager.loading(false);
        } else {
            this.uiManager.loading(false);
            this.flashMessenger.error(this.translator.trans('enhavo_app.error', {}, 'javascript'));
        }
    }

    morph(source: TabInterface): void
    {
        super.morph(source);
        this.revisions = (source as RevisionTab).revisions;
    }
}

export class Revision
{
    id: number;
    date: string;
    url: string;
}
