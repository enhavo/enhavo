import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {ClientInterface} from "@enhavo/app/client/ClientInterface";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {Translator} from "@enhavo/app/translation/Translator";
import {InputChangedEvent, ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";

export class MediaLibraryApplyAction extends AbstractAction
{
    public updateUrl: string;
    public applyUrl: string;
    public token: string;

    public confirmMessage: string;
    public confirmLabelOk: string;
    public confirmLabelCancel: string;

    constructor(
        private readonly frameManager: FrameManager,
        private readonly uiManager: UiManager,
        private readonly resourceInputManager: ResourceInputManager,
        private readonly flashMessenger: FlashMessenger,
        private readonly translator: Translator,
        private readonly client: ClientInterface,
    ) {
        super();
    }

    async execute(): Promise<void>
    {
        const accept = await this.uiManager.confirm({
            message: this.confirmMessage,
            denyLabel: this.confirmLabelCancel,
            acceptLabel: this.confirmLabelOk,
        });

        if (accept) {
            await this.doExecute();
        }
    }

    private async doExecute(): Promise<void>
    {
        this.uiManager.loading(true);

        let transport = await this.resourceInputManager.save(this.updateUrl, true);
        if (!transport.ok || !transport.response.ok) {
            await this.client.handleError(transport, {
                confirm: true,
                validation: true,
            });
        } else {
            this.flashMessenger.add(this.translator.trans('enhavo_app.input.message.save_success', {}, 'javascript'));

            transport = await this.resourceInputManager.sendForm(this.applyUrl);
            if (!transport.ok || !transport.response.ok) {
                await this.client.handleError(transport, {
                    confirm: true,
                    validation: true,
                });
            } else {
                this.flashMessenger.add(this.translator.trans('enhavo_media_library.input.message.apply_success', {}, 'javascript'));
                this.frameManager.dispatch(new InputChangedEvent(this.resourceInputManager.resource));
            }
        }

        this.uiManager.loading(false);
    }

}
