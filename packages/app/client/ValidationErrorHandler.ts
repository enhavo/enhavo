import {ClientInterface, ErrorHandlerInterface, Options, Transport} from "@enhavo/app/client/ClientInterface"
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {Translator} from "@enhavo/app/translation/Translator";

export class ValidationErrorHandler implements ErrorHandlerInterface
{
    constructor(
        private flashMessenger: FlashMessenger,
        private translator: Translator,
    ) {
    }

    handleError(transport: Transport, options: Options, client: ClientInterface): Promise<void>
    {
        return new Promise((resolve, reject) => {
            this.flashMessenger.error(this.translator.trans('enhavo_app.save.message.not_valid', {}, 'javascript'));
            resolve();
        })
    }

    supports(transport: Transport, options: Options, client: ClientInterface): boolean
    {
        return transport.ok && transport.response.status === 400 && options.validation;
    }
}
