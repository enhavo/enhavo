import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import NewsletterSendAction from "@enhavo/newsletter/action/model/NewsletterSendAction";
import ActionInterface from "@enhavo/app/action/ActionInterface";
import Form from "@enhavo/app/form/Form";
import Translator from "@enhavo/core/Translator";
import View from "@enhavo/app/view/View";
import Router from "@enhavo/core/Router";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";

export default class SaveActionFactory extends AbstractFactory
{
    private readonly form: Form;
    private readonly translator: Translator;
    private readonly view: View;
    private readonly router: Router;
    private readonly flashMessenger: FlashMessenger;

    constructor(form: Form, translator: Translator, view: View, router: Router, flashMessenger: FlashMessenger) {
        super();
        this.form = form;
        this.translator = translator;
        this.view = view;
        this.router = router;
        this.flashMessenger = flashMessenger;
    }

    createNew(): ActionInterface {
        return new NewsletterSendAction(this.form, this.translator, this.view, this.router, this.flashMessenger);
    }
}
