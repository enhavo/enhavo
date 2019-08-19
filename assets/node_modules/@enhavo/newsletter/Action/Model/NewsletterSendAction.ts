import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import * as $ from "jquery";
import Message from "@enhavo/app/FlashMessage/Message";
import axios from "axios";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import {FormApplication} from "@enhavo/app/Form/FormApplication";

export default class NewsletterSendAction extends AbstractAction
{
    public email: string;
    public resourceId: number;

    execute(): void
    {
        let id = this.resourceId;
        this.application.getView().loading();

        let url = this.application.getRouter().generate('enhavo_newsletter_newsletter_send', {
            id: id,
            email: this.email
        });
        axios.post(url, $('form').serialize(), {
            headers: {'Content-Type': 'multipart/form-data' }
        }).then((data) => {
            this.application.getView().loaded();
            this.getFlashMessanger().addMessage(new Message(data.data.type, data.data.message));
        })
    }

    private getFlashMessanger(): FlashMessenger
    {
        return (<FormApplication>this.application).getFlashMessenger();
    }

}