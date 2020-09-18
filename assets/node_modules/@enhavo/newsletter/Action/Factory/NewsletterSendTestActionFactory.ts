import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import NewsletterSendTestAction from "@enhavo/newsletter/Action/Model/NewsletterSendTestAction";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import Translator from "@enhavo/core/Translator";
import ModalManager from "@enhavo/app/Modal/ModalManager";

export default class NewsletterSendTestActionFactory extends AbstractFactory
{
    private readonly flashMessenger: FlashMessenger;
    private readonly translator: Translator;
    private readonly modalManager: ModalManager;

    constructor(flashMessenger: FlashMessenger, translator: Translator, modalManager: ModalManager) {
        super();
        this.flashMessenger = flashMessenger;
        this.translator = translator;
        this.modalManager = modalManager;
    }

    createNew(): NewsletterSendTestAction {
        return new NewsletterSendTestAction(this.flashMessenger, this.translator, this.modalManager);
    }
}
