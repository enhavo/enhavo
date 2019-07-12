import AbstractModal from "@enhavo/app/Modal/Model/AbstractModal";
import axios from 'axios';
import * as $ from 'jquery';
import {FormApplication} from "@enhavo/app/Form/FormApplication";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import Message from "@enhavo/app/FlashMessage/Message";

export default class NewsletterTestModal extends AbstractModal
{
    public email: string;

    send(): void
    {
        let id = this.application.getDataLoader().load().resource.id;
        this.application.getView().loading();
        let url = this.application.getRouter().generate('enhavo_newsletter_newsletter_test', {
            id: id,
            email: this.email
        });
        axios.post(url, $('form').serialize(), {
            headers: {'Content-Type': 'multipart/form-data' },
        }).then((data) => {
            this.application.getView().loaded();
            this.getFlashMessanger().addMessage(new Message(data.data.type, data.data.message));
            this.close();
        })
    }

    private getFlashMessanger(): FlashMessenger
    {
        return (<FormApplication>this.application).getFlashMessenger();
    }
}