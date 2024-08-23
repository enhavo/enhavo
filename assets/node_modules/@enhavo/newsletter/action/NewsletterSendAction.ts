import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import { FlashMessenger, FlashMessage } from "@enhavo/app/flash-message/FlashMessenger";
import axios from "axios";
import Confirm from "@enhavo/app/ui/Confirm";
import Form from "@enhavo/app/form/Form";
import Translator from "@enhavo/core/Translator";
import View from "@enhavo/app/view/View";
import Router from "@enhavo/core/Router";

export class NewsletterSendAction extends AbstractAction
{
    private readonly form: Form;
    private readonly translator: Translator;
    private readonly view: View;
    private readonly router: Router;
    private readonly flashMessenger: FlashMessenger;

    public resourceId: number;

    constructor(form: Form, translator: Translator, view: View, router: Router, flashMessenger: FlashMessenger) {
        super();
        this.form = form;
        this.translator = translator;
        this.view = view;
        this.router = router;
        this.flashMessenger = flashMessenger;
    }

    execute(): void
    {
        if (this.form !== null) {
            if(!this.form.isFormChanged()) {
                this.confirmAndSend();
            } else {
                this.view.alert(this.translator.trans('enhavo_newsletter.send.message.form_changed'));
            }
        } else {
            this.confirmAndSend();
        }
    }

    private confirmAndSend()
    {
        this.view.confirm(new Confirm(
            this.translator.trans('enhavo_newsletter.send.message.confirm'),
            () => { this.send() },
            () => {},
            this.translator.trans('enhavo_app.cancel'),
            this.translator.trans('enhavo_newsletter.send.action.send'),
        ));
    }

    private send()
    {
        this.view.loading();
        let url = this.router.generate('enhavo_newsletter_newsletter_send', {
            id: this.resourceId,
        });
        axios
            .post(url, {})
            .then((data) => {
                this.view.loaded();
                this.flashMessenger.add(data.data.message, data.data.type);
            })
            .catch((error) => {
                this.view.loaded();
                if(error.response.status == 400) {
                    this.flashMessenger.addMessage(new Message(
                        error.response.data.type,
                        error.response.data.message
                    ))
                } else {
                    this.flashMessenger.addMessage(new Message(
                        Message.ERROR,
                        this.translator.trans('enhavo_app.error')
                    ))
                }
            })
    }
}

