import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import NewsletterSendAction from "@enhavo/newsletter/Action/Model/NewsletterSendAction";
import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default class SaveActionFactory extends AbstractFactory
{
    createNew(): ActionInterface {
        return new NewsletterSendAction(this.application);
    }
}