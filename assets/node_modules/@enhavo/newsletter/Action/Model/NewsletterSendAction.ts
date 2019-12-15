import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import Message from "@enhavo/app/FlashMessage/Message";
import axios from "axios";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import {FormApplication} from "@enhavo/app/Form/FormApplication";
import Confirm from "@enhavo/app/View/Confirm";

export default class NewsletterSendAction extends AbstractAction
{
    public resourceId: number;

    execute(): void
    {
        let formApplication = <FormApplication>this.application;
        if(formApplication.getForm != null) {
            if(!formApplication.getForm().isFormChanged()) {
                this.confirmAndSend();
            } else {
                formApplication.getView().alert(this.application.getTranslator().trans('enhavo_newsletter.send.message.form_changed'));
            }
        } else {
            this.confirmAndSend();
        }
    }

    private confirmAndSend()
    {
        let formApplication = <FormApplication>this.application;
        formApplication.getView().confirm(new Confirm(
            this.application.getTranslator().trans('enhavo_newsletter.send.message.confirm'),
            () => { this.send() },
            () => {},
            this.application.getTranslator().trans('enhavo_app.cancel'),
            this.application.getTranslator().trans('enhavo_newsletter.send.action.send'),
        ));
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
                this.getFlashMessenger().addMessage(new Message(
                    data.data.type, data.data.message
                )
                );
            })
            .catch((error) => {
                this.application.getView().loaded();
                if(error.response.status == 400) {
                    this.getFlashMessenger().addMessage(new Message(
                        error.response.data.type,
                        error.response.data.message
                    ))
                } else {
                    this.getFlashMessenger().addMessage(new Message(
                        Message.ERROR,
                        this.application.getTranslator().trans('enhavo_app.error')
                    ))
                }
            })
    }

    private getFlashMessenger(): FlashMessenger
    {
        return (<FormApplication>this.application).getFlashMessenger();
    }
}

