import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
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
            const success = await this.resourceInputManager.save(this.url, true);
            if (success) {
                this.flashMessenger.add(this.translator.trans('enhavo_app.input.message.save_success', {}, 'javascript'));
                this.frameManager.dispatch(new Event('input_save'));
            } else {
                this.flashMessenger.error(this.translator.trans('enhavo_app.save.message.not_valid', {}, 'javascript'));
            }
        } catch (err) {
            console.error(err);
            this.uiManager.loading(false);
            this.uiManager.alert({
                message: this.translator.trans('enhavo_app.save.message.error', {}, 'javascript')
            });
        }

        this.uiManager.loading(false);
    }
}
