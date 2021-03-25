import UrlBatch from "@enhavo/app/grid/batch/model/UrlBatch";
import AjaxFormModal from "@enhavo/app/modal/model/AjaxFormModal";
import Message from "@enhavo/app/flash-message/Message";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import BatchDataInterface from "@enhavo/app/grid/batch/BatchDataInterface";
import Translator from "@enhavo/core/Translator";
import View from "@enhavo/app/view/View";
import Router from "@enhavo/core/Router";
import ModalManager from "@enhavo/app/modal/ModalManager";
import Grid from "@enhavo/app/grid/Grid";

export default class FormBatch extends UrlBatch
{
    public modal: any;

    protected readonly modalManager: ModalManager;
    protected readonly grid: Grid;

    public constructor(
        batchData: BatchDataInterface,
        translator: Translator,
        view: View,
        flashMessenger: FlashMessenger,
        router: Router,
        modalManager: ModalManager,
        grid: Grid
    ) {
        super(batchData, translator, view, flashMessenger, router);
        this.modalManager = modalManager;
        this.grid = grid;
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
                    this.flashMessenger.addMessage(new Message(Message.ERROR, data.data));
                    resolve(true);
                    return;
                } else if(error) {
                    this.flashMessenger.addMessage(new Message(Message.ERROR, this.translator.trans(error)));
                    resolve(true);
                    return;
                }

                this.flashMessenger.addMessage(new Message(Message.SUCCESS, this.translator.trans('enhavo_app.batch.message.success')));
                this.grid.loadTable();
                resolve(true);
            })
        };
        this.modalManager.push(this.modal);
        return false;
    }
}
