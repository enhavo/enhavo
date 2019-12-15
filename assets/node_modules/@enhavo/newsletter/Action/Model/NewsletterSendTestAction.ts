import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import axios from 'axios';
import * as $ from 'jquery';
import {FormApplication} from "@enhavo/app/Form/FormApplication";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import Message from "@enhavo/app/FlashMessage/Message";
import AjaxFormModal from "@enhavo/app/Modal/Model/AjaxFormModal";
import {IndexApplication} from "@enhavo/app/Index/IndexApplication";

export default class NewsletterSendTestAction extends AbstractAction
{
    public email: string;
    public modal: any;

    execute(): void
    {
        let data = $('form').serialize();
        this.modal.data = data;
        this.modal.actionHandler = (modal: AjaxFormModal, data: any, error: string) => {
            return new Promise((resolve, reject) => {
                if(data.status == 400) {
                    this.application.getFlashMessenger().addMessage(new Message(Message.ERROR, data.data));
                    resolve(true);
                    return;
                } else if(error) {
                    this.application.getFlashMessenger().addMessage(new Message(Message.ERROR, this.application.getTranslator().trans(error)));
                    resolve(true);
                    return;
                }

                this.application.getFlashMessenger().addMessage(new Message(Message.SUCCESS, this.application.getTranslator().trans('enhavo_app.batch.message.success')));
                (<IndexApplication>this.application).getGrid().loadTable();
                resolve(true);
            })
        };
        this.application.getModalManager().push(this.modal);
    }
}