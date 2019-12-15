import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import * as $ from 'jquery';
import Message from "@enhavo/app/FlashMessage/Message";
import AjaxFormModal from "@enhavo/app/Modal/Model/AjaxFormModal";
import {IndexApplication} from "@enhavo/app/Index/IndexApplication";

export default class NewsletterSendTestAction extends AbstractAction
{
    public email: string;
    public modal: any;

    execute(): void
    {
        let data = {
            form: $('form').serialize()
        }
        this.modal.data = data;
        this.modal.actionHandler = (modal: AjaxFormModal, data: any, error: string) => {
            return new Promise((resolve, reject) => {
                if(data.status == 400) {
                    this.application.getFlashMessenger().addMessage(new Message(data.response.data.type, data.response.data.message));
                    resolve(true);
                    return true;
                } else if(error) {
                    this.application.getFlashMessenger().addMessage(new Message(Message.ERROR, this.application.getTranslator().trans(error)));
                    resolve(true);
                    return true;
                }

                this.application.getFlashMessenger().addMessage(new Message(data.data.type, data.data.message));
                (<IndexApplication>this.application).getGrid().loadTable();
                resolve(true);
            })
        };
        this.application.getModalManager().push(this.modal);
    }
}