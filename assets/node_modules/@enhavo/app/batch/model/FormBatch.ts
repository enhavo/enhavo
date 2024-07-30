import { UrlBatch } from "@enhavo/app/batch/model/UrlBatch";
import { AjaxFormModal } from "@enhavo/app/modal/model/AjaxFormModal";
import { FlashMessenger, FlashMessage } from "@enhavo/app/flash-message/FlashMessenger";
import Translator from "@enhavo/core/Translator";
import View from "@enhavo/app/view/View";
import Router from "@enhavo/core/Router";
import { ModalManager } from "@enhavo/app/modal/ModalManager";

export class FormBatch extends UrlBatch
{
    public modal: any;

    public constructor(
        translator: Translator,
        view: View,
        flashMessenger: FlashMessenger,
        router: Router,
        private readonly modalManager: ModalManager,
    ) {
        super(translator, view, flashMessenger, router);
        this.modalManager = modalManager;
    }

    async execute(ids: number[]): Promise<boolean>
    {
        let idData = {};
        for(let i in ids) {
            idData[i] = ids[i];
        }

        this.modal.data = {
            ids: idData,
            type: this.key
        };

        this.modal.actionUrl = this.getUrl();
        this.modal.actionHandler = (modal: AjaxFormModal, data: any, error: string) => {
            return new Promise((resolve, reject) => {
                if(data.status == 400) {
                    this.flashMessenger.add( data.data, FlashMessage.ERROR);
                    resolve(true);
                    return;
                } else if(error) {
                    this.flashMessenger.add(this.translator.trans(error), FlashMessage.ERROR);
                    resolve(true);
                    return;
                }

                this.flashMessenger.add(this.translator.trans('enhavo_app.batch.message.success'));
                resolve(true);
            })
        };
        this.modalManager.push(this.modal);
        return false;
    }
}
