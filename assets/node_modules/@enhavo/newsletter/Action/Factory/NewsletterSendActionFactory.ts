import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import NewsletterSendAction from "@enhavo/newsletter/Action/Model/NewsletterSendAction";
import ActionInterface from "@enhavo/app/Action/ActionInterface";
import Form from "@enhavo/app/Form/Form";
import Translator from "@enhavo/core/Translator";
import View from "@enhavo/app/View/View";
import Router from "@enhavo/core/Router";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";

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
