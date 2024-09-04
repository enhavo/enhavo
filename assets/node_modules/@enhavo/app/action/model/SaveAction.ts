import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import {ResourceInputManager} from "../../manager/ResourceInputManager";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FlashMessenger} from "@enhavo/app/flash-message/UiManager";
import {Translator} from "@enhavo/app/translation/Translator";

export class SaveAction extends AbstractAction
{
    public url: string;

    constructor(
        private readonly frameManager: FrameManager,
        private readonly uiManager: UiManager,
        private readonly resourceInputManager: ResourceInputManager,
        private readonly flashMessenger: FlashMessenger,
        private readonly translator: Translator,
    ) {
        super();
    }

    async execute(): Promise<void>
    {
        this.uiManager.loading(true);
        try {
            await this.resourceInputManager.save(this.url, true);
            this.flashMessenger.add(this.translator.trans('enhavo_app.input.message.save_success', {}, 'javascript'));
        } catch (err) {
            console.error(err);
            this.uiManager.loading(false);
            this.uiManager.alert({
                message: 'An error occured'
            });
        }

        this.uiManager.loading(false);
    }
}
