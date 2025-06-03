import {AbstractBatch} from "@enhavo/app/batch/model/AbstractBatch";
import axios from "axios";
import {FlashMessenger, FlashMessage} from "@enhavo/app/flash-message/FlashMessenger";
import {Translator} from "@enhavo/app/translation/Translator";
import {UiManager} from "@enhavo/app/ui/UiManager";

export class UrlBatch extends AbstractBatch
{
    public url: string;
    public confirmMessage: string;
    public token: string;

    public constructor(
        protected readonly translator: Translator,
        protected readonly flashMessenger: FlashMessenger,
        protected readonly uiManager: UiManager,
    ) {
        super();
    }

    async execute(ids: number[]): Promise<boolean>
    {
        let data = {
            type: this.key,
            ids: ids,
            token: this.token,
        };

        return new Promise((resolve, reject) => {
            this.uiManager.confirm({
                message: this.confirmMessage,
                denyLabel: this.translator.trans('enhavo_app.view.label.cancel', {}, 'javascript'),
                acceptLabel: this.translator.trans('enhavo_app.view.label.ok', {}, 'javascript'),
            }).then((accept: boolean) => {
                if (accept) {
                    this.uiManager.loading(true);
                    axios.post(this.url, data)
                        .then((response) => {
                            this.uiManager.loading(false);
                            this.flashMessenger.add(this.translator.trans('enhavo_app.batch.message.success', {}, 'javascript'));
                            resolve(true);
                        })
                        .catch((error) => {
                            console.error(error);
                            this.uiManager.loading(false);
                            this.flashMessenger.add(this.translator.trans('enhavo_app.batch.message.error', {}, 'javascript'), FlashMessage.ERROR);
                            resolve(false);
                        })
                } else {
                    resolve(false);
                }
            }).catch((error) => {
                console.error(error);
                this.flashMessenger.add(this.translator.trans('enhavo_app.batch.message.error', {}, 'javascript'), FlashMessage.ERROR);
                resolve(false);
            });
        });
    }
}
