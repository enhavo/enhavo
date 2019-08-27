import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import Message from "@enhavo/app/FlashMessage/Message";
import axios from "axios";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import {FormApplication} from "@enhavo/app/Form/FormApplication";

export default class NewsletterSendAction extends AbstractAction
{
    public resourceId: number;

    execute(): void
    {
        let formApplication = <FormApplication>this.application;
        if(formApplication.getForm != null) {
            if(!formApplication.getForm().isFormChanged()) {
                this.send();
            } else {
                formApplication.getView().alert(this.application.getTranslator().trans('enhavo_newsletter.send.message.form_changed'));
            }
        } else {
            this.send();
        }
    }

    private send()
    {
        this.application.getView().loading();
        let url = this.application.getRouter().generate('enhavo_newsletter_newsletter_send', {
            id: this.resourceId,
        });
        axios
            .post(url, {})
            .then((data) => {
                this.application.getView().loaded();
                this.getFlashMessanger().addMessage(new Message(
                    data.data.type, data.data.message
                )
                );
            })
            .catch(error => {
                this.application.getView().loaded();
                this.getFlashMessanger().addMessage(new Message(
                    error.response.data.type,
                    error.response.data.message
                ))
            })
    }

    private getFlashMessanger(): FlashMessenger
    {
        return (<FormApplication>this.application).getFlashMessenger();
    }
}

