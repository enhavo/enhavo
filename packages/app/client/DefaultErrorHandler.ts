import {ClientInterface, ErrorHandlerInterface, Options, Transport} from "@enhavo/app/client/ClientInterface"
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Translator} from "@enhavo/app/translation/Translator";

export class DefaultErrorHandler implements ErrorHandlerInterface
{
    constructor(
        private uiManager: UiManager,
        private flashMessenger: FlashMessenger,
        private frameManager: FrameManager,
        private translator: Translator,
    ) {
    }

    handleError(transport: Transport, options: Options, client: ClientInterface): Promise<void>
    {
        if (!transport.ok) {
            console.error(transport.error);
        }

        return new Promise((resolve, reject) => {
            if (options.confirm) {
                this.uiManager.alert({
                    message: this.translator.trans('enhavo_app.error', {}, 'javascript')
                }).then(() => {
                    if (options.terminate) {
                        this.frameManager.close(true);
                    }
                    resolve();
                });
            } else {
                this.flashMessenger.error(this.translator.trans('enhavo_app.error', {}, 'javascript'));
                if (options.terminate) {
                    this.frameManager.close(true);
                }
                resolve();
            }
        })
    }

    supports(transport: Transport, options: Options, client: ClientInterface): boolean
    {
        return true;
    }
}
