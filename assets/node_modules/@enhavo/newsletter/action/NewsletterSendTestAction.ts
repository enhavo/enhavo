import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import $ from 'jquery';
import AjaxFormModal from "@enhavo/app/modal/model/AjaxFormModal";
import { FlashMessenger, FlashMessage } from "@enhavo/app/flash-message/FlashMessenger";
import Translator from "@enhavo/core/Translator";
import ModalManager from "@enhavo/app/modal/ModalManager";

export class NewsletterSendTestAction extends AbstractAction
{
    private flashMessenger: FlashMessenger;
    private translator: Translator;
    private modalManager: ModalManager;

    public email: string;
    public modal: any;

    constructor(flashMessenger: FlashMessenger, translator: Translator, modalManager: ModalManager) {
        super();
        this.flashMessenger = flashMessenger;
        this.translator = translator;
        this.modalManager = modalManager;
    }

    execute(): void
    {
        this.modal.data = {
            form: $('form').serialize()
        };

        this.modal.actionHandler = (modal: AjaxFormModal, data: any, error: string) => {
            return new Promise((resolve, reject) => {
                if(data.status == 400) {
                    this.flashMessenger.add(data.data.message, data.data.type);
                    resolve(false);
                    return;
                } else if(error) {
                    this.flashMessenger.add(this.translator.trans(error));
                    resolve(false);
                    return;
                }
                this.flashMessenger.add(data.data.message, data.data.type);
                resolve(true);
            })
        };

        this.modalManager.push(this.modal);
    }
}