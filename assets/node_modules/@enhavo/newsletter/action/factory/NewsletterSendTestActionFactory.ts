import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import NewsletterSendTestAction from "@enhavo/newsletter/action/model/NewsletterSendTestAction";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import Translator from "@enhavo/core/Translator";
import ModalManager from "@enhavo/app/modal/ModalManager";

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
