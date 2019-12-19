import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import * as $ from 'jquery';
import Message from "@enhavo/app/FlashMessage/Message";
import AjaxFormModal from "@enhavo/app/Modal/Model/AjaxFormModal";

export default class NewsletterSendTestAction extends AbstractAction
{
    public email: string;
    public modal: any;

    execute(): void
    {
        this.modal.data = {
            form: $('form').serialize()
        };
        this.modal.actionHandler = (modal: AjaxFormModal, data: any, error: string) => {
            return new Promise((resolve, reject) => {
                if(data.status == 400) {
                    this.application.getFlashMessenger().addMessage(new Message(data.data.type, data.data.message));
                    resolve(false);
                    return;
                } else if(error) {
                    this.application.getFlashMessenger().addMessage(new Message(Message.ERROR, this.application.getTranslator().trans(error)));
                    resolve(false);
                    return;
                }
                this.application.getFlashMessenger().addMessage(new Message(data.data.type, data.data.message));
                resolve(true);
            })
        };
        this.application.getModalManager().push(this.modal);
    }
}