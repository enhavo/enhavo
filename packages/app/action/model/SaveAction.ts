import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import {ResourceInputManager, InputChangedEvent} from "@enhavo/app/manager/ResourceInputManager";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {Translator} from "@enhavo/app/translation/Translator";
import {ClientInterface} from "@enhavo/app/client/ClientInterface";

export class SaveAction extends AbstractAction
{
    public url: string;

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
        this.uiManager.loading(true);

        const transport = await this.resourceInputManager.save(null, true);
        this.uiManager.loading(false);

        if (!transport.ok || !transport.response.ok) {
            await this.client.handleError(transport, {
                confirm: true,
                validation: true,
            });
            return;
        }

        this.flashMessenger.add(this.translator.trans('enhavo_app.input.message.save_success', {}, 'javascript'));
        this.frameManager.dispatch(new InputChangedEvent(this.resourceInputManager.resource));
    }
}
